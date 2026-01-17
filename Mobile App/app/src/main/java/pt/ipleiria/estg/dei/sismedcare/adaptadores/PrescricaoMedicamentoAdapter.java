package pt.ipleiria.estg.dei.sismedcare.adaptadores;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.R;
import pt.ipleiria.estg.dei.sismedcare.modelo.PrescricaoMedicamento;

public class PrescricaoMedicamentoAdapter extends RecyclerView.Adapter<PrescricaoMedicamentoAdapter.ViewHolder> {

    private List<PrescricaoMedicamento> lista;

    public PrescricaoMedicamentoAdapter(List<PrescricaoMedicamento> lista) {
        this.lista = lista;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_medicamento, parent, false);
        return new ViewHolder(v);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        PrescricaoMedicamento med = lista.get(position);
        holder.tvNome.setText(med.getNome());
        holder.tvPosologia.setText("Posologia: " + med.getPosologia());
        holder.tvFrequencia.setText("Frequência: " + med.getFrequencia());
        holder.tvDuracao.setText("Duração: " + med.getDuracaoDias() + " dias");
        holder.tvInstrucoes.setText("Instruções: " + med.getInstrucoes());
    }

    @Override
    public int getItemCount() {
        return lista.size();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {

        TextView tvNome, tvPosologia, tvFrequencia, tvDuracao, tvInstrucoes;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvNome = itemView.findViewById(R.id.tvNome);
            tvPosologia = itemView.findViewById(R.id.tvPosologia);
            tvFrequencia = itemView.findViewById(R.id.tvFrequencia);
            tvDuracao = itemView.findViewById(R.id.tvDuracao);
            tvInstrucoes = itemView.findViewById(R.id.tvInstrucoes);
        }
    }
}
