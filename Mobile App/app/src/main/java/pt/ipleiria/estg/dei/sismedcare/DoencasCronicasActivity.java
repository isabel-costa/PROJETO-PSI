package pt.ipleiria.estg.dei.sismedcare;

import android.os.Bundle;
import android.util.Log;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.Arrays;
import java.util.Collections;
import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.adaptadores.DoencasAdapter;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class DoencasCronicasActivity extends AppCompatActivity {

    private RecyclerView rvDoencas;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_doencas_cronicas);

        rvDoencas = findViewById(R.id.rvDoencas);
        rvDoencas.setLayoutManager(new LinearLayoutManager(this));

        ImageView ivVoltar = findViewById(R.id.btnVoltar);
        ivVoltar.setOnClickListener(v -> finish());

        carregarDoencas();
    }

    private void carregarDoencas() {
        SingletonGestorAPI.getInstance(this)
                .getDoencas(this, new SingletonGestorAPI.DoencasListener() {
                    @Override
                    public void onSuccess(String doencasStr) {
                        if (doencasStr == null || doencasStr.isEmpty()) {
                            Toast.makeText(DoencasCronicasActivity.this, "Sem doenças crónicas", Toast.LENGTH_SHORT).show();
                            return;
                        }

                        // Transformar string "Doença1, Doença2" em lista
                        List<String> listaDoencas = Arrays.asList(doencasStr.split(",\\s*"));

                        // Configurar adapter
                        DoencasAdapter adapter = new DoencasAdapter(DoencasCronicasActivity.this, listaDoencas);
                        rvDoencas.setAdapter(adapter);
                    }

                    @Override
                    public void onError(String erro) {
                        Toast.makeText(DoencasCronicasActivity.this, "Erro: " + erro, Toast.LENGTH_SHORT).show();
                        Log.e("DoencasCronicasActivity", erro);
                    }
                });
    }
}