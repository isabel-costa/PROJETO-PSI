package pt.ipleiria.estg.dei.sismedcare.modelo;

import android.content.Context;
import android.content.SharedPreferences;
import android.util.Base64;
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
import pt.ipleiria.estg.dei.sismedcare.PrescricoesActivity;
import pt.ipleiria.estg.dei.sismedcare.RegistarContaActivity;

import com.android.volley.toolbox.JsonArrayRequest;
import org.json.JSONArray;
import org.json.JSONException;


public class SingletonGestorAPI {

    private static final String TAG = "SingletonGestorAPI";

    // SharedPreferences
    private static final String PREFS_NAME = "sismedcare_prefs";
    private static final String KEY_USERNAME = "auth_username";
    private static final String KEY_PASSWORD = "auth_password";
    private static final String KEY_NOME = "paciente_nome";
    private static final String KEY_NUM_UTENTE = "paciente_num_utente";
    private static final String KEY_EMAIL = "paciente_email";
    private static final String KEY_TELEMOVEL = "paciente_telemovel";
    private static final String KEY_MORADA = "paciente_morada";

    private static SingletonGestorAPI instance = null;
    private static RequestQueue volleyQueue;

    private Paciente pacienteAutenticado;

    // Credenciais BasicAuth
    private String authUsername;
    private String authPassword;

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

    private void guardarSessao(Context context, Paciente paciente, String username, String password) {

        authUsername = username;
        authPassword = password;
        pacienteAutenticado = paciente;

        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
                .edit()
                .putString(KEY_USERNAME, username)
                .putString(KEY_PASSWORD, password)
                .putString(KEY_NOME, paciente.getNomeCompleto())
                .putString(KEY_NUM_UTENTE, paciente.getNumeroUtente())
                .putString(KEY_EMAIL, paciente.getUser() != null ? paciente.getUser().getEmail() : "")
                .putString(KEY_TELEMOVEL, paciente.getTelemovel())
                .putString(KEY_MORADA, paciente.getMorada())
                .apply();
    }

    private void restaurarSessao(Context context) {
        SharedPreferences prefs = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);

        authUsername = prefs.getString(KEY_USERNAME, null);
        authPassword = prefs.getString(KEY_PASSWORD, null);

        if (authUsername == null || authPassword == null) return;

        String nome = prefs.getString(KEY_NOME, "");
        String numUtente = prefs.getString(KEY_NUM_UTENTE, "");
        String email = prefs.getString(KEY_EMAIL, "");
        String telemovel = prefs.getString(KEY_TELEMOVEL, "");
        String morada = prefs.getString(KEY_MORADA, "");

        pacienteAutenticado = new Paciente(
                0,
                0,
                nome,
                "",
                "",
                numUtente,
                telemovel,
                morada,
                0,
                0,
                "",
                "",
                "",
                new User(0, authUsername, email, 0, 0, 0, "")
        );
    }

    public void logout(Context context) {
        authUsername = null;
        authPassword = null;
        pacienteAutenticado = null;

        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE).edit().clear().apply();
    }

    public String getAuthHeader() {
        if (authUsername == null || authPassword == null) return null;
        String credentials = authUsername + ":" + authPassword;
        return "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
    }

    public Paciente getPacienteAutenticado() {
        return pacienteAutenticado;
    }

    public String getBaseApiUrl() {
        return "http://172.22.21.219/SisMedCare/backend/web/api";
    }

    public void login(String username, String password, Context context) {
        String url = getBaseApiUrl() + "/auth/login";

        StringRequest request = new StringRequest(
                Request.Method.POST,
                url,
                response -> {
                    Log.d(TAG, "Resposta da API: " + response);

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

                        User user = new User(
                                0,
                                jsonUser.optString("username"),
                                jsonUser.optString("email"),
                                0,
                                0,
                                0,
                                jsonUser.optString("auth_key")
                        );

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
                                user
                        );

                        guardarSessao(context, paciente, username, password);
                        ((LoginActivity) context).onLoginSucesso();

                    } catch (Exception e) {
                        Log.e(TAG, "Erro login", e);
                        ((LoginActivity) context).onLoginErro("Erro ao processar dados");
                    }
                },
                error -> {
                    Log.e(TAG, "Erro de login", error);
                    ((LoginActivity) context).onLoginErro("Erro de ligação ao servidor");
                }
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

        // 1️⃣ Buscar a prescrição
        JsonObjectRequest requestPrescricao = new JsonObjectRequest(
                Request.Method.GET,
                urlPrescricao,
                null,
                response -> {
                    try {
                        // Nome do médico e data da prescrição
                        String data = response.getString("data_prescricao");
                        JSONObject medicoObj = response.getJSONObject("medico");
                        String nomeMedico = medicoObj.optString("nome", "Desconhecido");

                        // 2️⃣ Buscar os medicamentos associados
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

                                            // Criar PrescricaoMedicamento
                                            PrescricaoMedicamento pm = new PrescricaoMedicamento(
                                                    med.getString("nome"),
                                                    m.getString("posologia"),
                                                    m.getString("frequencia"),
                                                    m.getInt("duracao_dias"),
                                                    m.getString("instrucoes")
                                            );

                                            listaMeds.add(pm);
                                        }

                                        // Criar objeto Prescricao final
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

}
