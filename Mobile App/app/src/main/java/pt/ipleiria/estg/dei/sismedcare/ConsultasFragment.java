package pt.ipleiria.estg.dei.sismedcare;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.cardview.widget.CardView;
import androidx.fragment.app.Fragment;

public class ConsultasFragment extends Fragment {

    public ConsultasFragment() {
        // Construtor vazio obrigatório
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater,
                             @Nullable ViewGroup container,
                             @Nullable Bundle savedInstanceState) {

        return inflater.inflate(R.layout.fragment_consultas, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        CardView btnVerConsultas = view.findViewById(R.id.btn_consultas_VerConsultas);

        btnVerConsultas.setOnClickListener(v -> {
            Intent intent = new Intent(getActivity(), ConsultasVerConsultasActivity.class);
            startActivity(intent);
        });
    }
}