package pt.ipleiria.estg.dei.sismedcare;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

import pt.ipleiria.estg.dei.sismedcare.modelo.Paciente;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class HomepageFragment extends Fragment {

    private TextView tvNomeCompleto, tvNumUtente;
    private LinearLayout llProximasConsultas; // container para os cards

    @Nullable
    @Override
    public View onCreateView(
            @NonNull LayoutInflater inflater,
            @Nullable ViewGroup container,
            @Nullable Bundle savedInstanceState) {

        // Inflar o fragment principal
        View view = inflater.inflate(R.layout.fragment_homepage, container, false);

        // Inicializar Views
        tvNomeCompleto = view.findViewById(R.id.tv_nome_completo);
        tvNumUtente = view.findViewById(R.id.tv_num_utente);
        llProximasConsultas = view.findViewById(R.id.ll_container_consultas);

        ImageView btnPerfil = view.findViewById(R.id.btn_consultas_Perfil);
        btnPerfil.setOnClickListener(v -> {
            Intent intent = new Intent(getContext(), PerfilActivity.class);
            startActivity(intent);
        });

        // Preencher dados do paciente
        Paciente paciente = SingletonGestorAPI.getInstance(requireContext()).getPacienteAutenticado();
        if (paciente != null) {
            tvNomeCompleto.setText(paciente.getNomeCompleto());
            tvNumUtente.setText("Nº de utente: " + paciente.getNumeroUtente());
        }

        // Carregar consultas futuras
        carregarProximasConsultas();

        return view;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Carregar paciente autenticado do SharedPreferences se existir
        SingletonGestorAPI api = SingletonGestorAPI.getInstance(requireContext());
        if (api.getPacienteAutenticado() == null) {
            String username = api.getAuthUsername();
            String password = api.getAuthPassword();
            if (username != null && password != null) {
                api.login(username, password, requireActivity());
            }
        }
    }

    private void carregarProximasConsultas() {

        String url = SingletonGestorAPI.getInstance(requireContext()).getBaseApiUrl() + "/consultas/futuras";

        RequestQueue queue = Volley.newRequestQueue(requireContext());

        JsonArrayRequest request = new JsonArrayRequest(
                Request.Method.GET,
                url,
                null,
                response -> {
                    try {
                        llProximasConsultas.removeAllViews();

                        for (int i = 0; i < response.length(); i++) {
                            JSONObject consulta = response.getJSONObject(i);

                            String dataConsulta = consulta.getString("data_consulta");
                            String estado = consulta.getString("estado");
                            String tipo = consulta.getString("tipo");

                            View card = LayoutInflater.from(getContext())
                                    .inflate(R.layout.fragment_homepage, llProximasConsultas, false);

                            LinearLayout infoLayout = (LinearLayout) ((LinearLayout) card).getChildAt(1);

                            TextView txtTipo = (TextView) infoLayout.getChildAt(0);
                            TextView txtEstado = (TextView) infoLayout.getChildAt(1);
                            TextView txtHora = (TextView) infoLayout.getChildAt(2);

                            txtTipo.setText(tipo);
                            txtEstado.setText(estado);
                            txtHora.setText("Data: " + dataConsulta);

                            ImageView btnDelete = (ImageView) ((LinearLayout) card).getChildAt(2);

                            if (estado.equalsIgnoreCase("pendente") && consultaFutura(dataConsulta)) {
                                btnDelete.setVisibility(View.VISIBLE);
                            } else {
                                btnDelete.setVisibility(View.GONE);
                            }

                            llProximasConsultas.addView(card);
                        }

                    } catch (Exception e) {
                        e.printStackTrace();
                        Toast.makeText(getContext(), "Erro ao processar consultas", Toast.LENGTH_LONG).show();
                    }
                },
                error -> {
                    Toast.makeText(getContext(), "Erro ao carregar consultas", Toast.LENGTH_LONG).show();
                    Log.e("CONSULTAS", "Erro: " + error);
                }
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();

                String auth = SingletonGestorAPI.getInstance(requireContext()).getAuthHeader();

                if (auth != null) {
                    headers.put("Authorization", auth);
                }

                return headers;
            }
        };
    }

    // Verifica se a consulta é futura
    private boolean consultaFutura(String dataConsulta) {
        try {
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
            Date data = sdf.parse(dataConsulta);
            return data != null && data.after(new Date());
        } catch (Exception e) {
            return false;
        }
    }
}
