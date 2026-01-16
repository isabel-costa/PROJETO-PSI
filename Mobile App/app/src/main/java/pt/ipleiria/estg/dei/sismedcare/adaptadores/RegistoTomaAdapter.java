package pt.ipleiria.estg.dei.sismedcare.adaptadores;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.R;
import pt.ipleiria.estg.dei.sismedcare.modelo.RegistoToma;

public class RegistoTomaAdapter
        extends RecyclerView.Adapter<RegistoTomaAdapter.ViewHolder> {

    public interface OnTomaClickListener {
        void onMarcarComoTomada(RegistoToma toma);
    }

    private List<RegistoToma> lista;
    private OnTomaClickListener listener;

    public RegistoTomaAdapter(List<RegistoToma> lista,
                              OnTomaClickListener listener) {
        this.lista = lista;
        this.listener = listener;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_acompanhamento_medicacao_card, parent, false);
        return new ViewHolder(v);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder h, int position) {
        RegistoToma toma = lista.get(position);

        h.tvNome.setText(toma.getNomeMedicamento());
        h.tvQuantidade.setText("Quantidade: " + toma.getQuantidade());
        h.tvHora.setText("Hora: " + toma.getHora());

        if (toma.isFoiTomado()) {
            h.imgEstado.setImageResource(R.drawable.ic_tomada);
            h.imgEstado.setEnabled(false);
        } else {
            h.imgEstado.setImageResource(R.drawable.ic_adicionar);
            h.imgEstado.setEnabled(true);
            h.imgEstado.setOnClickListener(v -> {
                if (listener != null) {
                    listener.onMarcarComoTomada(toma);
                }
            });
        }
    }

    @Override
    public int getItemCount() {
        return lista.size();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvNome, tvQuantidade, tvHora;
        ImageView imgEstado;

        ViewHolder(View v) {
            super(v);
            tvNome = v.findViewById(R.id.tvNomeMedicamento);
            tvQuantidade = v.findViewById(R.id.tvQuantidade);
            tvHora = v.findViewById(R.id.tvHora);
            imgEstado = v.findViewById(R.id.imgEstado);
        }
    }
}
