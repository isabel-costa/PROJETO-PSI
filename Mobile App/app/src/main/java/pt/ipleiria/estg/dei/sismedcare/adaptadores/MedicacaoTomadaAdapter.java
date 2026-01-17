package pt.ipleiria.estg.dei.sismedcare.adaptadores;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;
import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.R;

import pt.ipleiria.estg.dei.sismedcare.modelo.RegistoToma;

public class MedicacaoTomadaAdapter extends RecyclerView.Adapter<MedicacaoTomadaAdapter.ViewHolder> {

    private List<RegistoToma> listaMedicamentos;

    public MedicacaoTomadaAdapter(List<RegistoToma> lista) {
        this.listaMedicamentos = lista;
    }

    public MedicacaoTomadaAdapter() {
        this.listaMedicamentos = new ArrayList<>();
    }

    public void setLista(List<RegistoToma> novaLista) {
        listaMedicamentos.clear();
        listaMedicamentos.addAll(novaLista);
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_medicacao_tomada_card, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        RegistoToma rt = listaMedicamentos.get(position);

        holder.tvNome.setText(rt.getNomeMedicamento());
        holder.tvQuantidade.setText("Quantidade: " + rt.getQuantidade());
        holder.tvHora.setText("Hora: " + rt.getHora());
        holder.checkIcon.setImageResource(R.drawable.ic_tomada);
    }

    @Override
    public int getItemCount() {
        return listaMedicamentos.size();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvNome, tvQuantidade, tvHora;
        ImageView checkIcon;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvNome = itemView.findViewById(R.id.tvNomeMedicamento);
            tvQuantidade = itemView.findViewById(R.id.tvQuantidade);
            tvHora = itemView.findViewById(R.id.tvHora);
            checkIcon = itemView.findViewById(R.id.checkIcon);
        }
    }
}