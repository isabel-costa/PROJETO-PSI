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

public class DoencasAdapter extends RecyclerView.Adapter<DoencasAdapter.DoencaViewHolder> {

    private Context context;
    private List<String> doencas;

    public DoencasAdapter(Context context, List<String> doencas) {
        this.context = context;
        this.doencas = doencas;
    }

    @NonNull
    @Override
    public DoencaViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_doenca_card, parent, false);
        return new DoencaViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull DoencaViewHolder holder, int position) {
        String doenca = doencas.get(position);
        holder.tvDoenca.setText(doenca);
    }

    @Override
    public int getItemCount() {
        return doencas.size();
    }

    static class DoencaViewHolder extends RecyclerView.ViewHolder {
        TextView tvDoenca;

        public DoencaViewHolder(@NonNull View itemView) {
            super(itemView);
            tvDoenca = itemView.findViewById(R.id.tvDoenca);
        }
    }
}
