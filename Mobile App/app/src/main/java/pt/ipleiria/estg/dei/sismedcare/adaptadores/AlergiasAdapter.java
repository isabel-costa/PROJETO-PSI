package pt.ipleiria.estg.dei.sismedcare.adaptadores;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import pt.ipleiria.estg.dei.sismedcare.R;

public class AlergiasAdapter extends RecyclerView.Adapter<AlergiasAdapter.AlergiaViewHolder> {

    private Context context;
    private List<String> alergias;

    public AlergiasAdapter(Context context, List<String> alergias) {
        this.context = context;
        this.alergias = alergias;
    }

    @NonNull
    @Override
    public AlergiaViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_alergia_card, parent, false);
        return new AlergiaViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull AlergiaViewHolder holder, int position) {
        String alergia = alergias.get(position);
        holder.tvAlergia.setText(alergia);
    }

    @Override
    public int getItemCount() {
        return alergias.size();
    }

    static class AlergiaViewHolder extends RecyclerView.ViewHolder {
        TextView tvAlergia;

        public AlergiaViewHolder(@NonNull View itemView) {
            super(itemView);
            tvAlergia = itemView.findViewById(R.id.tvAlergia);
        }
    }
}