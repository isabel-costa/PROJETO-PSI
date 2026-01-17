package pt.ipleiria.estg.dei.sismedcare;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;
import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.adaptadores.PrescricaoAdapter;
import pt.ipleiria.estg.dei.sismedcare.adaptadores.RegistoTomaAdapter;
import pt.ipleiria.estg.dei.sismedcare.modelo.Prescricao;
import pt.ipleiria.estg.dei.sismedcare.modelo.RegistoToma;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

import android.content.Intent;
import android.widget.ImageView;
import android.widget.TextView;

public class PrescricaoMedicamentoFragment extends Fragment {

    private RecyclerView rvPrescricoes;
    private TextView tvVerTudo;
    private ImageView btnPerfil;
    private RecyclerView rvMedicacaoPendente;

    public PrescricaoMedicamentoFragment() {
        // Construtor vazio obrigatório
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater,
                             @Nullable ViewGroup container,
                             @Nullable Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_prescricao_medicamento, container, false);

        rvPrescricoes = view.findViewById(R.id.rvPrescricoes);
        rvPrescricoes.setLayoutManager(new LinearLayoutManager(getContext()));

        rvMedicacaoPendente = view.findViewById(R.id.rvMedicacaoPendente);
        rvMedicacaoPendente.setLayoutManager(new LinearLayoutManager(getContext()));

        // Perfil
        btnPerfil = view.findViewById(R.id.btn_prescricoes_Perfil);
        btnPerfil.setOnClickListener(v -> {
            Intent intent = new Intent(getContext(), PerfilActivity.class);
            startActivity(intent);
        });

        // Ver Tudo
        tvVerTudo = view.findViewById(R.id.tvVerTudoPrescricoes);
        tvVerTudo.setOnClickListener(v -> {
            Intent intent = new Intent(getContext(), PrescricoesActivity.class);
            startActivity(intent);
        });

        carregarPrescricaoMaisRecente();
        carregarMedicacaoPendente();

        return view;
    }

    private void carregarPrescricaoMaisRecente() {
        SingletonGestorAPI.getInstance(getContext())
                .getPrescricoes(getContext(), new SingletonGestorAPI.PrescricoesListener() {
                    @Override
                    public void onSuccess(List<Prescricao> prescricoes) {

                        if (prescricoes == null || prescricoes.isEmpty()) {
                            Toast.makeText(getContext(), "Sem prescrições", Toast.LENGTH_SHORT).show();
                            return;
                        }

                        // Mais recente
                        Prescricao maisRecente = prescricoes.get(0);

                        List<Prescricao> listaUma = new ArrayList<>();
                        listaUma.add(maisRecente);

                        PrescricaoAdapter adapter = new PrescricaoAdapter(getContext(), listaUma);

                        rvPrescricoes.setAdapter(adapter);
                    }

                    @Override
                    public void onError(String erro) {
                        Toast.makeText(getContext(), erro, Toast.LENGTH_SHORT).show();
                    }
                });
    }

    private void carregarMedicacaoPendente() {

        SingletonGestorAPI.getInstance(getContext())
                .getRegistoTomasPendentes(getContext(),
                        new SingletonGestorAPI.RegistoTomaListener() {
                            @Override
                            public void onSuccess(List<RegistoToma> lista) {

                                if (lista.isEmpty()) {
                                    Toast.makeText(getContext(),
                                            "Sem medicação pendente",
                                            Toast.LENGTH_SHORT).show();
                                    return;
                                }

                                RegistoTomaAdapter adapter =
                                        new RegistoTomaAdapter(lista, null);

                                rvMedicacaoPendente.setAdapter(adapter);
                            }

                            @Override
                            public void onError(String erro) {
                                Toast.makeText(getContext(), erro, Toast.LENGTH_SHORT).show();
                            }
                        });
    }


}
