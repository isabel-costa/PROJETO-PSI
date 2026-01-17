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

    // Credenciais BasicAuth
    private String authUsername;
    private String authPassword;
    private String authToken;

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

    public String getBaseApiUrl() {
        return "http://10.0.2.2/PROJETO-PSI/Web%20App/SisMedCare/backend/web/api";
    }

    public void login(String username, String password, Context context) {
        String url = getBaseApiUrl() + "/auth/login";

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
        String url = getBaseApiUrl() + "/auth/registar";

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

    public String getConsultasFuturasUrl() {
        return getBaseApiUrl() + "/consultas/futuras";
    }

    public String getConsultasPassadasUrl() {
        return getBaseApiUrl() + "/consultas/passadas";
    }

    public String getAuthUsername() {
        return authUsername;
    }

    public String getAuthPassword() {
        return authPassword;
    }

    public interface ConsultasListener {
        void onSuccess(List<Consulta> consultas);

        void onError(String erro);
    }

    public void getConsultasFuturas(Context context, ConsultasListener listener) {
        JsonArrayRequest request = new JsonArrayRequest(
                Request.Method.GET,
                getConsultasFuturasUrl(),
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
                getConsultasPassadasUrl(),
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

    public void cancelarPedidoConsulta(int consultaId, ConsultaDeleteListener listener) {
        String url = getBaseApiUrl() + "/consultas/" + consultaId;

        JsonObjectRequest request = new JsonObjectRequest(
                Request.Method.DELETE,
                url,
                null,
                response -> {
                    if (listener != null) listener.onSuccess();
                },
                error -> {
                    if (listener != null) listener.onError(error.toString());
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
        String url = getBaseApiUrl() + "/prescricao";

        JsonArrayRequest request = new JsonArrayRequest(
                Request.Method.GET,
                url,
                null,
                response -> {
                    List<Prescricao> lista = new ArrayList<>();
                    for (int i = 0; i < response.length(); i++) {
                        try {
                            JSONObject obj = response.getJSONObject(i);
                            Prescricao p = new Prescricao(
                                    obj.getInt("id"),
                                    obj.getString("data_prescricao"),
                                    obj.getJSONObject("medico").optString("nome", "Desconhecido"),
                                    new ArrayList<>()
                            );
                            lista.add(p);
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                    listener.onSuccess(lista);
                },
                error -> listener.onError("Erro ao carregar prescrições")
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

    public void getPrescricaoDetalhes(int prescricaoId, PrescricaoDetalhesListener listener) {
        String urlPrescricao = getBaseApiUrl() + "/prescricao/" + prescricaoId;
        String urlMedicamentos = getBaseApiUrl() + "/prescricao-medicamento/prescricao/" + prescricaoId;

        JsonObjectRequest requestPrescricao = new JsonObjectRequest(
                Request.Method.GET,
                urlPrescricao,
                null,
                response -> {
                    try {
                        String data = response.getString("data_prescricao");
                        JSONObject medicoObj = response.getJSONObject("medico");
                        String nomeMedico = medicoObj.optString("nome", "Desconhecido");

                        JsonArrayRequest requestMeds = new JsonArrayRequest(
                                Request.Method.GET,
                                urlMedicamentos,
                                null,
                                medsArray -> {
                                    try {
                                        List<PrescricaoMedicamento> listaMeds = new ArrayList<>();

                                        for (int i = 0; i < medsArray.length(); i++) {
                                            JSONObject m = medsArray.getJSONObject(i);
                                            JSONObject med = m.getJSONObject("medicamento");

                                            PrescricaoMedicamento pm = new PrescricaoMedicamento(
                                                    med.getString("nome"),
                                                    m.getString("posologia"),
                                                    m.getString("frequencia"),
                                                    m.getInt("duracao_dias"),
                                                    m.getString("instrucoes")
                                            );

                                            listaMeds.add(pm);
                                        }

                                        Prescricao prescricao = new Prescricao(
                                                response.getInt("id"),
                                                data,
                                                nomeMedico,
                                                listaMeds
                                        );

                                        listener.onSuccess(prescricao);

                                    } catch (JSONException e) {
                                        e.printStackTrace();
                                        listener.onError("Erro ao processar medicamentos");
                                    }
                                },
                                error -> listener.onError("Erro ao carregar medicamentos")
                        ) {
                            @Override
                            public Map<String, String> getHeaders() {
                                Map<String, String> headers = new HashMap<>();
                                headers.put("Authorization", getAuthHeader());
                                return headers;
                            }
                        };

                        volleyQueue.add(requestMeds);

                    } catch (JSONException e) {
                        e.printStackTrace();
                        listener.onError("Erro ao processar prescrição");
                    }
                },
                error -> listener.onError("Erro ao carregar prescrição")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Authorization", getAuthHeader());
                return headers;
            }
        };

        volleyQueue.add(requestPrescricao);
    }

    public interface DoencasListener {
        void onSuccess(String doencas);

        void onError(String erro);
    }

    public void getDoencas(Context context, DoencasListener listener) {
        String url = getBaseApiUrl() + "/paciente/doencas";

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
        String url = getBaseApiUrl() + "/paciente/alergias";

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

    public void getRegistoTomasPendentes(Context context,
                                         RegistoTomaListener listener) {

        String url = getBaseApiUrl() + "/registo-toma/pendentes";

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

    public void marcarTomaComoTomada(RegistoToma toma, TomaTomadaListener listener) {
        String url = getBaseApiUrl() + "/registo-toma/marcar/" + toma.getId();

        JSONObject body = new JSONObject();
        try {
            body.put("id", toma.getId());
        } catch (JSONException e) {
            e.printStackTrace();
            listener.onError("Erro ao criar solicitação");
            return;
        }

        JsonObjectRequest request = new JsonObjectRequest(
                Request.Method.POST,
                url,
                body,
                response -> listener.onSuccess(),
                error -> listener.onError("Erro ao marcar medicação como tomada")
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Authorization", getAuthHeader());
                headers.put("Content-Type", "application/json");
                return headers;
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