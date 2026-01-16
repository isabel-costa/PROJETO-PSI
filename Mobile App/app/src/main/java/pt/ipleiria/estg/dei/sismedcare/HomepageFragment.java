package pt.ipleiria.estg.dei.sismedcare;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.adaptadores.ConsultaAdapter;
import pt.ipleiria.estg.dei.sismedcare.modelo.Consulta;
import pt.ipleiria.estg.dei.sismedcare.modelo.Paciente;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class HomepageFragment extends Fragment {

    private TextView tvNomeCompleto, tvNumUtente;
    private RecyclerView rvProximasConsultas;

    @Nullable
    @Override
    public View onCreateView(
            @NonNull android.view.LayoutInflater inflater,
            @Nullable android.view.ViewGroup container,
            @Nullable Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_homepage, container, false);

        // Inicializar views
        tvNomeCompleto = view.findViewById(R.id.tv_nome_completo);
        tvNumUtente = view.findViewById(R.id.tv_num_utente);
        rvProximasConsultas = view.findViewById(R.id.rvProximasConsultas);
        rvProximasConsultas.setLayoutManager(new LinearLayoutManager(getContext()));

        // Botão de perfil
        ImageView btnPerfil = view.findViewById(R.id.btn_consultas_Perfil);
        btnPerfil.setOnClickListener(v -> startActivity(new Intent(getContext(), PerfilActivity.class)));

        // Outras navegações
        view.findViewById(R.id.ll_doencas_cronicas).setOnClickListener(v -> startActivity(new Intent(getContext(), DoencasCronicasActivity.class)));

        view.findViewById(R.id.ll_prescricao).setOnClickListener(v -> startActivity(new Intent(getContext(), PrescricoesActivity.class)));

        view.findViewById(R.id.ll_alergias).setOnClickListener(v -> startActivity(new Intent(getContext(), AlergiasActivity.class)));

        view.findViewById(R.id.ll_marcacao_consultas).setOnClickListener(v -> startActivity(new Intent(getContext(), MarcarConsultasActivity.class)));

        view.findViewById(R.id.ll_ver_consultas).setOnClickListener(v -> startActivity(new Intent(getContext(), ConsultasVerConsultasActivity.class)));

        // Preencher dados do paciente
        Paciente paciente = SingletonGestorAPI.getInstance(requireContext()).getPacienteAutenticado();
        if (paciente != null) {
            tvNomeCompleto.setText(paciente.getNomeCompleto());
            tvNumUtente.setText("Nº de utente: " + paciente.getNumeroUtente());
        }

        // Carregar consultas futuras via API
        carregarProximasConsultas();

        return view;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Garantir login do paciente
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
        SingletonGestorAPI api = SingletonGestorAPI.getInstance(requireContext());

        api.getConsultasFuturas(getContext(), new SingletonGestorAPI.ConsultasListener() {
            @Override
            public void onSuccess(List<Consulta> consultas) {
                if (consultas != null && !consultas.isEmpty()) {
                    // Preencher RecyclerView
                    rvProximasConsultas.setAdapter(new ConsultaAdapter(consultas));
                }
            }

            @Override
            public void onError(String erro) {
                Toast.makeText(getContext(), "Erro ao carregar consultas: " + erro, Toast.LENGTH_LONG).show();
                Log.e("CONSULTAS", "Erro ao carregar consultas: " + erro);
            }
        });
    }
}
