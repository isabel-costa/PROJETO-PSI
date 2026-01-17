package pt.ipleiria.estg.dei.sismedcare.adaptadores;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import pt.ipleiria.estg.dei.sismedcare.R;
import pt.ipleiria.estg.dei.sismedcare.modelo.Consulta;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class ConsultaAdapter extends RecyclerView.Adapter<ConsultaAdapter.ViewHolder> {

    private final List<Consulta> consultas;

    public ConsultaAdapter(List<Consulta> consultas) {
        this.consultas = consultas;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_consulta_card, parent, false);
        return new ViewHolder(v);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Consulta c = consultas.get(position);

        // Exemplo se a data for 2026-01-17 10:00:00
        String[] partes = c.getDataConsulta().split(" ");
        String[] data = partes[0].split("-");

        holder.tvAno.setText(data[0]);
        holder.tvMes.setText(getMesAbreviado(Integer.parseInt(data[1])));
        holder.tvDia.setText(data[2]);
        holder.tvHora.setText("Hora: " + partes[1].substring(0, 5));

        holder.tvEstado.setText(c.getEstado());
        holder.tvTipo.setText(c.getObservacoes());

        // Mostrar o botão delete apenas se a consulta for futura e pendente
        if (c.getEstado().equalsIgnoreCase("pendente") && consultaFutura(c.getDataConsulta())) {
            holder.btnDelete.setVisibility(View.VISIBLE);
        } else {
            holder.btnDelete.setVisibility(View.GONE);
        }

        // Listener do delete com AlertDialog
        holder.btnDelete.setOnClickListener(v -> {
            int pos = holder.getAdapterPosition();
            if (pos == RecyclerView.NO_POSITION) return;

            Consulta cAtual = consultas.get(pos);

            // Mostrar confirmação
            new androidx.appcompat.app.AlertDialog.Builder(v.getContext())
                    .setTitle("Cancelar consulta")
                    .setMessage("Tem a certeza que quer cancelar esta consulta?")
                    .setPositiveButton("Sim", (dialog, which) -> {

                        SingletonGestorAPI api = SingletonGestorAPI.getInstance(v.getContext());
                        api.cancelarPedidoConsulta(cAtual.getId(), new SingletonGestorAPI.ConsultaDeleteListener() {
                            @Override
                            public void onSuccess() {
                                // Remove do RecyclerView
                                consultas.remove(pos);
                                notifyItemRemoved(pos);
                                Toast.makeText(v.getContext(), "Consulta cancelada com sucesso", Toast.LENGTH_SHORT).show();
                            }

                            @Override
                            public void onError(String erro) {
                                // Mostrar erro da API
                                Toast.makeText(v.getContext(), "Erro ao cancelar pedido de consulta: " + erro, Toast.LENGTH_LONG).show();
                            }
                        }, v.getContext());

                    }).setNegativeButton("Não", null).show();
        });
    }

    @Override
    public int getItemCount() {
        return consultas.size();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvMes, tvDia, tvAno, tvTipo, tvEstado, tvHora;
        ImageView btnDelete;

        ViewHolder(View itemView) {
            super(itemView);
            tvMes = itemView.findViewById(R.id.tv_mes_consulta);
            tvDia = itemView.findViewById(R.id.tv_dia_consulta);
            tvAno = itemView.findViewById(R.id.tv_ano_consulta);
            tvTipo = itemView.findViewById(R.id.txt_tipo_consulta);
            tvEstado = itemView.findViewById(R.id.txt_estado_consulta);
            tvHora = itemView.findViewById(R.id.txt_hora_consulta);
            btnDelete = itemView.findViewById(R.id.btn_delete_consulta);
        }
    }

    private String getMesAbreviado(int mes) {
        String[] meses = {"Jan.", "Fev.", "Mar.", "Abr.", "Mai.", "Jun.",
                "Jul.", "Ago.", "Set.", "Out.", "Nov.", "Dez."};
        return meses[mes - 1];
    }

    // Função para verificar se a consulta é futura
    private boolean consultaFutura(String dataConsulta) {
        try {
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
            Date data = sdf.parse(dataConsulta);
            return data != null && data.after(new Date());
        } catch (Exception e) {
            return false;
        }
    }
}