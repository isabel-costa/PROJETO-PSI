package pt.ipleiria.estg.dei.sismedcare;

import android.os.Bundle;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.adaptadores.PrescricaoMedicamentoAdapter;
import pt.ipleiria.estg.dei.sismedcare.modelo.Prescricao;
import pt.ipleiria.estg.dei.sismedcare.modelo.PrescricaoMedicamento;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class DetalhesPrescricaoActivity extends AppCompatActivity {

    private RecyclerView rvMedicamentos;
    private PrescricaoMedicamentoAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detalhes_prescricao);

        RelativeLayout containerPadding = findViewById(R.id.containerPadding);

        ViewCompat.setOnApplyWindowInsetsListener(containerPadding, (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());

            v.setPadding(
                    v.getPaddingLeft(),
                    systemBars.top,
                    v.getPaddingRight(),
                    v.getPaddingBottom()
            );

            return insets;
        });

        rvMedicamentos = findViewById(R.id.rvMedicamentos);
        rvMedicamentos.setLayoutManager(new LinearLayoutManager(this));

        ImageView ivVoltar = findViewById(R.id.ivVoltar);
        ivVoltar.setOnClickListener(v -> finish());

        int prescricaoId = getIntent().getIntExtra("prescricao_id", -1);
        if (prescricaoId == -1) {
            Toast.makeText(this, "Prescrição inválida", Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        // Buscar detalhes da prescrição
        SingletonGestorAPI.getInstance(this)
                .getPrescricaoDetalhes(
                        this,
                        prescricaoId,
                        new SingletonGestorAPI.PrescricaoDetalhesListener() {

                            @Override
                            public void onSuccess(Prescricao prescricao) {

                                List<PrescricaoMedicamento> meds =
                                        prescricao.getMedicamentos();

                                if (meds == null || meds.isEmpty()) {
                                    Toast.makeText(
                                            DetalhesPrescricaoActivity.this,
                                            "Sem medicamentos",
                                            Toast.LENGTH_SHORT
                                    ).show();
                                    return;
                                }

                                adapter = new PrescricaoMedicamentoAdapter(meds);
                                rvMedicamentos.setAdapter(adapter);
                            }

                            @Override
                            public void onError(String erro) {
                                Toast.makeText(
                                        DetalhesPrescricaoActivity.this,
                                        erro,
                                        Toast.LENGTH_SHORT
                                ).show();
                            }
                        }
                );
    }
}