package pt.ipleiria.estg.dei.sismedcare;

import android.os.Bundle;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;
import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.adaptadores.PrescricaoAdapter;
import pt.ipleiria.estg.dei.sismedcare.modelo.Prescricao;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class PrescricoesActivity extends AppCompatActivity {

    private RecyclerView recyclerView;
    private PrescricaoAdapter adapter;
    private List<Prescricao> listaPrescricoes;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_prescricoes);

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        // RecyclerView
        recyclerView = findViewById(R.id.rvPrescricoes);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        ImageView ivVoltar = findViewById(R.id.ivVoltar);
        ivVoltar.setOnClickListener(v -> finish());

        listaPrescricoes = new ArrayList<>();
        adapter = new PrescricaoAdapter(this, listaPrescricoes);

        recyclerView.setAdapter(adapter);

        carregarPrescricoes();
    }

    private void carregarPrescricoes() {
        SingletonGestorAPI.getInstance(this).getPrescricoes(this, new SingletonGestorAPI.PrescricoesListener() {
            @Override
            public void onSuccess(List<Prescricao> prescricoes) {
                listaPrescricoes.clear();
                listaPrescricoes.addAll(prescricoes);
                adapter.notifyDataSetChanged();
            }

            @Override
            public void onError(String erro) {
                Toast.makeText(PrescricoesActivity.this, erro, Toast.LENGTH_SHORT).show();
            }
        });
    }
}
