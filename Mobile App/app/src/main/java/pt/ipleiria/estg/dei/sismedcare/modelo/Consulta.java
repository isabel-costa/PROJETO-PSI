package pt.ipleiria.estg.dei.sismedcare.modelo;

public class Consulta {
    private int id;
    private String dataConsulta; // "YYYY-MM-DD HH:MM:SS"
    private String estado;
    private String observacoes;

    public Consulta(int id, String dataConsulta, String estado, String observacoes) {
        this.id = id;
        this.dataConsulta = dataConsulta;
        this.estado = estado;
        this.observacoes = observacoes;
    }

    // Getters e Setters
    public int getId() { return id; }
    public String getDataConsulta() { return dataConsulta; }
    public String getEstado() { return estado; }
    public String getObservacoes() { return observacoes; }

    public void setId(int id) { this.id = id; }
    public void setDataConsulta(String dataConsulta) { this.dataConsulta = dataConsulta; }
    public void setEstado(String estado) { this.estado = estado; }
    public void setObservacoes(String observacoes) { this.observacoes = observacoes; }
}
