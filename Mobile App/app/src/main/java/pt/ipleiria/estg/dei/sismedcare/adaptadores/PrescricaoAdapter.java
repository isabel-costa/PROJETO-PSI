package pt.ipleiria.estg.dei.sismedcare.adaptadores;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.ImageView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.DetalhesPrescricaoActivity;
import pt.ipleiria.estg.dei.sismedcare.R;
import pt.ipleiria.estg.dei.sismedcare.modelo.Prescricao;

public class PrescricaoAdapter extends RecyclerView.Adapter<PrescricaoAdapter.PrescricaoViewHolder> {

    private Context context;
    private List<Prescricao> lista;

    public PrescricaoAdapter(Context context, List<Prescricao> lista) {
        this.context = context;
        this.lista = lista;
    }

    @NonNull
    @Override
    public PrescricaoViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_prescricao_card, parent, false);
        return new PrescricaoViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull PrescricaoViewHolder holder, int position) {
        Prescricao p = lista.get(position);
        holder.tvData.setText("Data: " + p.getDataPrescricao());
        holder.tvMedico.setText("Médico: " + p.getNomeMedico());

        holder.itemView.setOnClickListener(v -> {
            // Abrir DetalhesPrescricaoActivity
            Intent intent = new Intent(context, DetalhesPrescricaoActivity.class);
            intent.putExtra("prescricao_id", p.getId());
            context.startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return lista.size();
    }

    static class PrescricaoViewHolder extends RecyclerView.ViewHolder {
        TextView tvData, tvMedico;
        ImageView imgEstado;

        public PrescricaoViewHolder(@NonNull View itemView) {
            super(itemView);
            tvData = itemView.findViewById(R.id.tvData);
            tvMedico = itemView.findViewById(R.id.tvMedico);
            imgEstado = itemView.findViewById(R.id.imgEstado);
        }
    }
}
