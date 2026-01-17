package pt.ipleiria.estg.dei.sismedcare;

import android.os.Bundle;
import android.util.Log;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.Arrays;
import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.adaptadores.AlergiasAdapter;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class AlergiasActivity extends AppCompatActivity {

    private RecyclerView rvAlergias;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_alergias);

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

        rvAlergias = findViewById(R.id.rvAlergias);
        rvAlergias.setLayoutManager(new LinearLayoutManager(this));

        ImageView btnVoltar = findViewById(R.id.btnVoltar);
        btnVoltar.setOnClickListener(v -> finish());

        carregarAlergias();
    }

    private void carregarAlergias() {
        SingletonGestorAPI.getInstance(this)
                .getAlergias(this, new SingletonGestorAPI.AlergiasListener() {
                    @Override
                    public void onSuccess(String alergiasStr) {
                        if (alergiasStr == null || alergiasStr.isEmpty()) {
                            Toast.makeText(AlergiasActivity.this, "Sem alergias registadas", Toast.LENGTH_SHORT).show();
                            return;
                        }

                        // Transformar string "Alergia1, Alergia2" em lista
                        List<String> listaAlergias = Arrays.asList(alergiasStr.split(",\\s*"));

                        // Configurar adapter
                        AlergiasAdapter adapter = new AlergiasAdapter(AlergiasActivity.this, listaAlergias);
                        rvAlergias.setAdapter(adapter);
                    }

                    @Override
                    public void onError(String erro) {
                        Toast.makeText(AlergiasActivity.this, "Erro: " + erro, Toast.LENGTH_SHORT).show();
                        Log.e("AlergiasActivity", erro);
                    }
                });
    }
}