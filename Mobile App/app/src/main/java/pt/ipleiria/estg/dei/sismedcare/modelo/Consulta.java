package pt.ipleiria.estg.dei.sismedcare.modelo;

public class Consulta {
    private int id;
    private int pacienteId;
    private int medicoId;
    private String dataConsulta; // "YYYY-MM-DD HH:MM:SS"
    private String estado;
    private String observacoes;
    private String nomeMedico;
    private String especialidadeMedico;

    public Consulta(int id, int pacienteId, int medicoId, String dataConsulta, String estado, String observacoes,
                    String nomeMedico, String especialidadeMedico) {
        this.id = id;
        this.pacienteId = pacienteId;
        this.medicoId = medicoId;
        this.dataConsulta = dataConsulta;
        this.estado = estado;
        this.observacoes = observacoes;
        this.nomeMedico = nomeMedico;
        this.especialidadeMedico = especialidadeMedico;
    }

    // Getters e Setters
    public int getId() { return id; }
    public int getPacienteId() { return pacienteId; }
    public int getMedicoId() { return medicoId; }
    public String getDataConsulta() { return dataConsulta; }
    public String getEstado() { return estado; }
    public String getObservacoes() { return observacoes; }
    public String getNomeMedico() { return nomeMedico; }
    public String getEspecialidadeMedico() { return especialidadeMedico; }

    public void setId(int id) { this.id = id; }
    public void setPacienteId(int pacienteId) { this.pacienteId = pacienteId; }
    public void setMedicoId(int medicoId) { this.medicoId = medicoId; }
    public void setDataConsulta(String dataConsulta) { this.dataConsulta = dataConsulta; }
    public void setEstado(String estado) { this.estado = estado; }
    public void setObservacoes(String observacoes) { this.observacoes = observacoes; }
    public void setNomeMedico(String nomeMedico) { this.nomeMedico = nomeMedico; }
    public void setEspecialidadeMedico(String especialidadeMedico) { this.especialidadeMedico = especialidadeMedico; }
}
