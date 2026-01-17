package pt.ipleiria.estg.dei.sismedcare.modelo;

import android.content.Context;
import android.content.SharedPreferences;
import android.util.Log;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import pt.ipleiria.estg.dei.sismedcare.LoginActivity;
import pt.ipleiria.estg.dei.sismedcare.RegistarContaActivity;

import com.android.volley.toolbox.JsonArrayRequest;
import org.json.JSONArray;
import org.json.JSONException;

import pt.ipleiria.estg.dei.sismedcare.utils.NetworkUtils;


public class SingletonGestorAPI {

    private static final String TAG = "SingletonGestorAPI";

    // SharedPreferences
    private static final String PREFS_NAME = "sismedcare_prefs";
    private static final String KEY_TOKEN = "auth_token";
    private static final String KEY_PACIENTE_NOME = "paciente_nome";
    private static final String KEY_PACIENTE_NUM_UTENTE = "paciente_num_utente";

    private static SingletonGestorAPI instance = null;
    private static RequestQueue volleyQueue;

    private List<RegistoToma> tomasTomadas = new ArrayList<>();

    private Paciente pacienteAutenticado;

    // Credenciais BearerAuth
    private  String authToken;

    private SingletonGestorAPI(Context context) {
        volleyQueue = Volley.newRequestQueue(context.getApplicationContext());
        restaurarSessao(context);
    }

    public static synchronized SingletonGestorAPI getInstance(Context context) {
        if (instance == null) {
            instance = new SingletonGestorAPI(context);
        }
        return instance;
    }

    private void guardarSessao(Context context, Paciente paciente, String token) {
        this.pacienteAutenticado = paciente;
        this.authToken = token;

        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE).edit().putString(KEY_TOKEN, token).putString(KEY_PACIENTE_NOME, paciente.getNomeCompleto()).putString(KEY_PACIENTE_NUM_UTENTE, paciente.getNumeroUtente()).apply();
    }

    private void restaurarSessao(Context context) {
        SharedPreferences prefs = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        authToken = prefs.getString(KEY_TOKEN, null);

        if (authToken == null) return;

        String nome = prefs.getString(KEY_PACIENTE_NOME, "");
        String numUtente = prefs.getString(KEY_PACIENTE_NUM_UTENTE, "");

        pacienteAutenticado = new Paciente(
                0,
                0,
                nome,
                "",
                "",
                numUtente,
                "",
                "",
                0,
                0,
                "",
                "",
                "",
                null
        );
    }

    public void logout(Context context) {
        authToken = null;
        pacienteAutenticado = null;

        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE).edit().clear().apply();
    }

    public String getAuthHeader() {
        if (authToken == null) return null;
        return "Bearer " + authToken;
    }

    public boolean isLoggedIn() {
        return authToken != null && !authToken.isEmpty();
    }

    public Paciente getPacienteAutenticado() {
        return pacienteAutenticado;
    }

    public String getBaseApiUrl(Context context) {
        SharedPreferences prefs = context.getSharedPreferences("CONFIG_APP", Context.MODE_PRIVATE);
        String urlServidor = prefs.getString("URL_SERVIDOR", "http://172.22.21.215");
        String caminhoAPI = prefs.getString("CAMINHO_API", "/SisMedCare/backend/web/api");

        // Garantir que não sobra "/" duplo
        if (urlServidor.endsWith("/")) urlServidor = urlServidor.substring(0, urlServidor.length() - 1);
        if (caminhoAPI.startsWith("/")) caminhoAPI = caminhoAPI.substring(1);

        return urlServidor + "/" + caminhoAPI;
    }

    public void login(String username, String password, Context context) {
        String url = getBaseApiUrl(context) + "/auth/login";

        StringRequest request = new StringRequest(
                Request.Method.POST,
                url,
                response -> {
                    try {
                        JSONObject obj = new JSONObject(response);

                        if (!obj.optBoolean("success", false)) {
                            ((LoginActivity) context).onLoginErro(
                                    obj.optString("message", "Erro no login")
                            );
                            return;
                        }

                        JSONObject jsonUser = obj.getJSONObject("user");
                        JSONObject jsonPaciente = jsonUser.getJSONObject("paciente");

                        String token = jsonUser.getString("auth_key");

                        Paciente paciente = new Paciente(
                                0,
                                0,
                                jsonPaciente.optString("nome_completo"),
                                jsonPaciente.optString("data_nascimento", ""),
                                jsonPaciente.optString("sexo", ""),
                                jsonPaciente.optString("numero_utente", ""),
                                jsonPaciente.optString("telemovel", ""),
                                jsonPaciente.optString("morada", ""),
                                (float) jsonPaciente.optDouble("altura", 0),
                                (float) jsonPaciente.optDouble("peso", 0),
                                jsonPaciente.optString("alergias", ""),
                                jsonPaciente.optString("doencas_cronicas", ""),
                                jsonPaciente.optString("data_registo", ""),
                                null
                        );

                        guardarSessao(context, paciente, token);
                        ((LoginActivity) context).onLoginSucesso();

                    } catch (Exception e) {
                        ((LoginActivity) context).onLoginErro("Erro ao processar dados");
                    }
                },
                error -> ((LoginActivity) context).onLoginErro("Erro de ligação ao servidor")
        ) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("username", username);
                params.put("password", password);
                return params;
            }
        };

        volleyQueue.add(request);
    }

    public void registarPaciente(
            Context context,
            Map<String, String> dados,
            RegistarContaActivity activity
    ) {
        String url = getBaseApiUrl(context) + "/auth/registar";

        StringRequest request = new StringRequest(
                Request.Method.POST,
                url,
                response -> {
                    try {
                        JSONObject obj = new JSONObject(response);

                        if (obj.has("error")) {
                            activity.onRegistoErro(obj.getString("error"));
                        } else {
                            activity.onRegistoSucesso();
                        }
                    } catch (Exception e) {
                        Log.e(TAG, "Erro registo", e);
                        activity.onRegistoErro("Erro ao processar resposta");
                    }
                },
                error -> {
                    Log.e(TAG, "Erro de registo", error);
                    activity.onRegistoErro("Erro de ligação ao servidor");
                }
        ) {
            @Override
            protected Map<String, String> getParams() {
                return dados;
            }
        };

        volleyQueue.add(request);
    }

    public String getConsultasFuturasUrl(Context context) {
        return getBaseApiUrl(context) + "/consultas/futuras";
    }

    public String getConsultasPassadasUrl(Context context) {
        return getBaseApiUrl(context) + "/consultas/passadas";
    }

    public interface ConsultasListener {
        void onSuccess(List<Consulta> consultas);

        void onError(String erro);
    }

    public void getConsultasFuturas(Context context, ConsultasListener listener) {

        /* String baseUrl = getBaseApiUrl(context);
        Log.d("SingletonGestorAPI", "Base URL atual: " + baseUrl); */

        JsonArrayRequest request = new JsonArrayRequest(
                Request.Method.GET,
                getConsultasFuturasUrl(context),
                null,
                response -> listener.onSuccess(parseConsultas(response)),
                error -> listener.onError("Erro ao carregar consultas futuras")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Authorization", getAuthHeader());
                return headers;
            }
        };

        volleyQueue.add(request);
    }

    public void getConsultasPassadas(Context context, ConsultasListener listener) {
        JsonArrayRequest request = new JsonArrayRequest(
                Request.Method.GET,
                getConsultasPassadasUrl(context),
                null,
                response -> listener.onSuccess(parseConsultas(response)),
                error -> listener.onError("Erro ao carregar consultas passadas")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Authorization", getAuthHeader());
                return headers;
            }
        };

        volleyQueue.add(request);
    }

    private List<Consulta> parseConsultas(JSONArray response) {
        List<Consulta> lista = new ArrayList<>();

        for (int i = 0; i < response.length(); i++) {
            try {
                JSONObject obj = response.getJSONObject(i);

                Consulta c = new Consulta(
                        obj.getInt("id"),
                        obj.getString("data_consulta"),
                        obj.getString("estado"),
                        obj.optString("observacoes", "")
                );

                lista.add(c);

            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
        return lista;
    }

    public interface ConsultaDeleteListener {
        void onSuccess();

        void onError(String erro);
    }

    public void cancelarPedidoConsulta(int consultaId, ConsultaDeleteListener listener, Context context) {
        String url = getBaseApiUrl(context) + "/consultas/" + consultaId;

        // Usar StringRequest para não parsear JSON
        StringRequest request = new StringRequest(
                Request.Method.DELETE,
                url,
                response -> {
                    // A API devolve 204 No Content → response vazio
                    if (listener != null) listener.onSuccess();
                },
                error -> {
                    if (listener != null) {
                        // passa o status ou mensagem
                        String msg = (error.networkResponse != null)
                                ? "Código: " + error.networkResponse.statusCode
                                : error.toString();
                        listener.onError(msg);
                    }
                }
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                String auth = getAuthHeader();
                if (auth != null) headers.put("Authorization", auth);
                return headers;
            }
        };

        volleyQueue.add(request);
    }

    public interface MedicoEspecialidadeListener {
        void onSuccess(org.json.JSONArray response);
        void onError(String erro);
    }

    public void getMedicoEspecialidade(String url, MedicoEspecialidadeListener listener) {
        JsonArrayRequest request = new JsonArrayRequest(
                Request.Method.GET,
                url,
                null,
                response -> listener.onSuccess(response),
                error -> listener.onError("Erro ao carregar dados")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                String auth = getAuthHeader();
                if (auth != null) {
                    headers.put("Authorization", auth);
                }
                return headers;
            }
        };

        volleyQueue.add(request);
    }

    public interface PrescricoesListener {
        void onSuccess(List<Prescricao> prescricoes);

        void onError(String erro);
    }

    public interface PrescricaoDetalhesListener {
        void onSuccess(Prescricao prescricao);

        void onError(String erro);
    }

    public void getPrescricoes(Context context, PrescricoesListener listener) {

        PrescricaoBDHelper bd = new PrescricaoBDHelper(context);

        if (!NetworkUtils.hasInternet(context)) {
            // 🔴 OFFLINE
            List<Prescricao> lista = bd.getAllPrescricoes();
            if (lista.isEmpty()) listener.onError("Sem ligação à internet");
            else listener.onSuccess(lista);
            return;
        }

        // 🌐 ONLINE
        String url = getBaseApiUrl(context) + "/prescricao";
        JsonArrayRequest request = new JsonArrayRequest(
                url,
                response -> {
                    try {
                        List<Prescricao> lista = new ArrayList<>();

                        for (int i = 0; i < response.length(); i++) {
                            JSONObject obj = response.getJSONObject(i);

                            Prescricao p = new Prescricao(
                                    obj.getInt("id"),
                                    obj.getString("data_prescricao"),
                                    obj.getJSONObject("medico").optString("nome", "Desconhecido"),
                                    new ArrayList<>()
                            );

                            lista.add(p);

                            // 🔒 salva offline
                            bd.guardarPrescricao(p);
                        }

                        listener.onSuccess(lista);

                    } catch (Exception e) {
                        e.printStackTrace();
                        listener.onError("Erro ao processar prescrições");
                    }
                },
                error -> listener.onError("Erro de ligação à API")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String,String> h = new HashMap<>();
                h.put("Authorization", getAuthHeader());
                return h;
            }
        };

        volleyQueue.add(request);
    }

    public void getPrescricaoDetalhes(Context context, int prescricaoId, PrescricaoDetalhesListener listener) {

        PrescricaoBDHelper bd = new PrescricaoBDHelper(context);

        if (!NetworkUtils.hasInternet(context)) {
            // 🔴 OFFLINE
            Prescricao p = bd.getPrescricaoById(prescricaoId);
            if (p == null || p.getMedicamentos().isEmpty()) listener.onError("Sem ligação à internet");
            else listener.onSuccess(p);
            return;
        }

        // 🌐 ONLINE
        String url = getBaseApiUrl(context) + "/prescricao-medicamento/prescricao/" + prescricaoId;

        JsonArrayRequest request = new JsonArrayRequest(
                url,
                response -> {
                    try {
                        List<PrescricaoMedicamento> meds = new ArrayList<>();
                        String dataPrescricao = "";

                        for (int i = 0; i < response.length(); i++) {
                            JSONObject obj = response.getJSONObject(i);

                            if (obj.has("prescricao")) {
                                dataPrescricao = obj.getJSONObject("prescricao").optString("data_prescricao","");
                            }

                            JSONObject medObj = obj.getJSONObject("medicamento");

                            PrescricaoMedicamento pm = new PrescricaoMedicamento(
                                    medObj.optString("nome"),
                                    obj.optString("posologia"),
                                    obj.optString("frequencia"),
                                    obj.optInt("duracao_dias"),
                                    obj.optString("instrucoes")
                            );

                            meds.add(pm);
                        }

                        // 🔒 salva offline
                        Prescricao pOffline = new Prescricao(prescricaoId, dataPrescricao, "", meds);
                        bd.guardarPrescricao(pOffline);

                        listener.onSuccess(pOffline);

                    } catch (Exception e) {
                        e.printStackTrace();
                        listener.onError("Erro ao processar medicamentos");
                    }
                },
                error -> listener.onError("Erro de ligação ao servidor")
        ) {
            @Override
            public Map<String,String> getHeaders() {
                Map<String,String> h = new HashMap<>();
                h.put("Authorization", getAuthHeader());
                return h;
            }
        };

        volleyQueue.add(request);
    }

    public interface DoencasListener {
        void onSuccess(String doencas);

        void onError(String erro);
    }

    public void getDoencas(Context context, DoencasListener listener) {
        String url = getBaseApiUrl(context) + "/paciente/doencas";

        JsonObjectRequest request = new JsonObjectRequest(
                Request.Method.GET,
                url,
                null,
                response -> {
                    try {
                        String doencas = response.optString("doencas_cronicas", "");
                        listener.onSuccess(doencas);
                    } catch (Exception e) {
                        e.printStackTrace();
                        listener.onError("Erro ao processar resposta da API");
                    }
                },
                error -> {
                    error.printStackTrace();
                    listener.onError("Erro ao carregar doenças crónicas");
                }
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                String auth = getAuthHeader();
                if (auth != null) {
                    headers.put("Authorization", auth);
                }
                return headers;
            }
        };

        volleyQueue.add(request);
    }

    public interface AlergiasListener {
        void onSuccess(String alergias);

        void onError(String erro);
    }

    public void getAlergias(Context context, AlergiasListener listener) {
        String url = getBaseApiUrl(context) + "/paciente/alergias";

        JsonObjectRequest request = new JsonObjectRequest(
                Request.Method.GET,
                url,
                null,
                response -> {
                    String alergiasStr = response.optString("alergias", "");
                    listener.onSuccess(alergiasStr);
                },
                error -> listener.onError("Erro ao carregar alergias")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Authorization", getAuthHeader());
                return headers;
            }
        };

        volleyQueue.add(request);
    }

    public interface RegistoTomaListener {
        void onSuccess(List<RegistoToma> lista);

        void onError(String erro);
    }

    public void getRegistoTomasPendentes(Context context, RegistoTomaListener listener) {

        String url = getBaseApiUrl(context) + "/registo-toma/pendentes";

        JsonArrayRequest request = new JsonArrayRequest(
                Request.Method.GET,
                url,
                null,
                response -> {
                    try {
                        List<RegistoToma> lista = new ArrayList<>();

                        for (int i = 0; i < response.length(); i++) {
                            JSONObject obj = response.getJSONObject(i);

                            String id = obj.getString("id");
                            int prescricaoMedicamentoId =
                                    obj.getInt("prescricao_medicamento_id");

                            String hora =
                                    obj.getString("data_toma").substring(11, 16);

                            boolean foiTomado =
                                    obj.getInt("foi_tomado") == 1;

                            RegistoToma toma = new RegistoToma(
                                    id,
                                    "",
                                    "",
                                    hora,
                                    foiTomado
                            );

                            lista.add(toma);

                            String urlPrescricao = getBaseApiUrl()
                                    + "/prescricao-medicamento/"
                                    + prescricaoMedicamentoId;

                            JsonObjectRequest prescricaoRequest =
                                    new JsonObjectRequest(
                                            Request.Method.GET,
                                            urlPrescricao,
                                            null,
                                            prescricaoResponse -> {
                                                try {
                                                    String nomeMedicamento =
                                                            prescricaoResponse
                                                                    .getJSONObject("medicamento")
                                                                    .getString("nome");

                                                    String frequencia =
                                                            prescricaoResponse
                                                                    .getString("frequencia");

                                                    toma.setNomeMedicamento(nomeMedicamento);
                                                    toma.setQuantidade(frequencia);

                                                    // refresca a UI
                                                    listener.onSuccess(lista);

                                                } catch (Exception e) {
                                                    e.printStackTrace();
                                                }
                                            },
                                            error -> {
                                            }
                                    ) {
                                        @Override
                                        public Map<String, String> getHeaders() {
                                            Map<String, String> headers = new HashMap<>();
                                            headers.put("Authorization", getAuthHeader());
                                            return headers;
                                        }
                                    };

                            volleyQueue.add(prescricaoRequest);
                        }

                        listener.onSuccess(lista);

                    } catch (Exception e) {
                        e.printStackTrace();
                        listener.onError("Erro ao processar tomas");
                    }
                },
                error -> listener.onError("Erro ao carregar tomas pendentes")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Authorization", getAuthHeader());
                return headers;
            }
        };

        volleyQueue.add(request);
    }

    public interface PerfilListener {
        void onSuccess(Paciente paciente);
        void onError(String erro);
    }

    public void getPerfilPaciente(Context context, PerfilListener listener) {
        String url = getBaseApiUrl(context) + "/paciente";

        JsonObjectRequest request = new JsonObjectRequest(
                Request.Method.GET,
                url,
                null,
                response -> {
                    try {
                        // Preenche paciente com os dados da API
                        Paciente paciente = new Paciente(
                                0, // id (não é usado aqui)
                                0, // userId
                                response.optString("nome_completo", ""),
                                response.optString("data_nascimento", ""),
                                response.optString("sexo", ""),
                                response.optString("numero_utente", ""),
                                response.optString("telemovel", ""),
                                response.optString("morada", ""),
                                0, // altura
                                0, // peso
                                "", // alergias
                                "", // doencasCronicas
                                "", // dataRegisto
                                null
                        );
                        paciente.setEmail(response.optString("email", ""));
                        listener.onSuccess(paciente);

                    } catch (Exception e) {
                        e.printStackTrace();
                        listener.onError("Erro ao processar perfil");
                    }
                },
                error -> listener.onError("Erro ao carregar perfil")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String,String> h = new HashMap<>();
                h.put("Authorization", getAuthHeader());
                return h;
            }
        };

        volleyQueue.add(request);
    }

    public interface TomaTomadaListener {
        void onSuccess();

        void onError(String erro);
    }

    public void adicionarToma(RegistoToma toma) {
        if (toma != null && !tomasTomadas.contains(toma)) {
            tomasTomadas.add(toma);
        }
    }

    public List<RegistoToma> getTomasTomadas() {
        return new ArrayList<>(tomasTomadas);
    }

    public void getRegistoTomasTomadas(Context context, RegistoTomaListener listener) {

        String url = getBaseApiUrl() + "/registo-toma/tomadas";

        JsonArrayRequest request = new JsonArrayRequest(
                Request.Method.GET,
                url,
                null,
                response -> {
                    try {
                        List<RegistoToma> lista = new ArrayList<>();

                        for (int i = 0; i < response.length(); i++) {
                            JSONObject obj = response.getJSONObject(i);

                            String id = obj.getString("id");
                            String hora = obj.getString("data_toma").substring(11, 16);
                            boolean foiTomado = obj.getInt("foi_tomado") == 1;

                            int prescricaoMedicamentoId =
                                    obj.getInt("prescricao_medicamento_id");

                            RegistoToma toma = new RegistoToma(
                                    id,
                                    "",
                                    "",
                                    hora,
                                    foiTomado
                            );

                            lista.add(toma);

                            String urlPrescricao = getBaseApiUrl()
                                    + "/prescricao-medicamento/"
                                    + prescricaoMedicamentoId;

                            JsonObjectRequest prescricaoRequest =
                                    new JsonObjectRequest(
                                            Request.Method.GET,
                                            urlPrescricao,
                                            null,
                                            prescricaoResponse -> {
                                                try {
                                                    String nomeMedicamento =
                                                            prescricaoResponse
                                                                    .getJSONObject("medicamento")
                                                                    .getString("nome");

                                                    String frequencia =
                                                            prescricaoResponse.getString("frequencia");

                                                    toma.setNomeMedicamento(nomeMedicamento);
                                                    toma.setQuantidade(frequencia);

                                                    listener.onSuccess(lista);

                                                } catch (Exception e) {
                                                    e.printStackTrace();
                                                }
                                            },
                                            error -> Log.e(TAG,
                                                    "Erro ao carregar prescrição", error)
                                    ) {
                                        @Override
                                        public Map<String, String> getHeaders() {
                                            Map<String, String> headers = new HashMap<>();
                                            headers.put("Authorization", getAuthHeader());
                                            return headers;
                                        }
                                    };

                            volleyQueue.add(prescricaoRequest);
                        }

                        listener.onSuccess(lista);

                    } catch (Exception e) {
                        e.printStackTrace();
                        listener.onError("Erro ao processar tomas tomadas");
                    }
                },
                error -> listener.onError("Erro ao carregar tomas tomadas")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Authorization", getAuthHeader());
                return headers;
            }
        };
        volleyQueue.add(request);
    }
}
    public interface PerfilUpdateListener {
        void onSuccess();
        void onError(String erro);
    }

    public void atualizarPerfil(Context context, Map<String,String> dados, PerfilUpdateListener listener) {
        String url = getBaseApiUrl(context) + "/paciente";

        JsonObjectRequest request = new JsonObjectRequest(
                Request.Method.PUT,
                url,
                new JSONObject(dados),
                response -> {
                    listener.onSuccess();
                },
                error -> {
                    String msg = (error.networkResponse != null)
                            ? "Código: " + error.networkResponse.statusCode
                            : error.toString();
                    listener.onError(msg);
                }
        ) {
            @Override
            public Map<String,String> getHeaders() {
                Map<String,String> h = new HashMap<>();
                String auth = getAuthHeader();
                if (auth != null) h.put("Authorization", auth);
                return h;
            }
        };

        volleyQueue.add(request);
    }
}
