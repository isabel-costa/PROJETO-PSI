package pt.ipleiria.estg.dei.sismedcare;

import android.os.Bundle;
import android.widget.ImageView;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;
import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.adaptadores.ConsultaAdapter;
import pt.ipleiria.estg.dei.sismedcare.modelo.Consulta;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class ConsultasVerConsultasActivity extends AppCompatActivity {

    private RecyclerView rvConsultasFuturas, rvConsultasPassadas;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_consultas_ver_consultas);

        ImageView btnVoltar = findViewById(R.id.ic_voltar);
        btnVoltar.setOnClickListener(v -> finish());

        rvConsultasFuturas = findViewById(R.id.rvConsultasFuturas);
        rvConsultasPassadas = findViewById(R.id.rvConsultasPassadas);

        rvConsultasFuturas.setLayoutManager(new LinearLayoutManager(this));
        rvConsultasPassadas.setLayoutManager(new LinearLayoutManager(this));

        carregarConsultas();
    }

    private void carregarConsultas() {
        SingletonGestorAPI api = SingletonGestorAPI.getInstance(this);

        // Consultas futuras
        api.getConsultasFuturas(this, new SingletonGestorAPI.ConsultasListener() {
            @Override
            public void onSuccess(List<Consulta> consultas) {
                rvConsultasFuturas.setAdapter(new ConsultaAdapter(consultas));
            }

            @Override
            public void onError(String erro) {
                // opcional: Toast
            }
        });

        // Consultas passadas
        api.getConsultasPassadas(this, new SingletonGestorAPI.ConsultasListener() {
            @Override
            public void onSuccess(List<Consulta> consultas) {
                rvConsultasPassadas.setAdapter(new ConsultaAdapter(consultas));
            }

            @Override
            public void onError(String erro) {
                // opcional: Toast
            }
        });
    }
}
