package pt.ipleiria.estg.dei.sismedcare;

import android.os.Bundle;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.adaptadores.MedicacaoTomadaAdapter;
import pt.ipleiria.estg.dei.sismedcare.modelo.PrescricaoMedicamento;
import pt.ipleiria.estg.dei.sismedcare.modelo.RegistoToma;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class MedicacaoTomadaActivity extends AppCompatActivity {

    private RecyclerView rvMedicacao;
    private MedicacaoTomadaAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_medicacao_tomada);

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

        rvMedicacao = findViewById(R.id.rvMedicacaoTomada);
        rvMedicacao.setLayoutManager(new LinearLayoutManager(this));

        adapter = new MedicacaoTomadaAdapter();
        rvMedicacao.setAdapter(adapter);

        SingletonGestorAPI.getInstance(this).getRegistoTomasTomadas(this, new SingletonGestorAPI.RegistoTomaListener() {
            @Override
            public void onSuccess(List<RegistoToma> lista) {
                adapter.setLista(lista);
            }

            @Override
            public void onError(String erro) {
                Toast.makeText(MedicacaoTomadaActivity.this, erro, Toast.LENGTH_LONG).show();
            }
        });
    }
}