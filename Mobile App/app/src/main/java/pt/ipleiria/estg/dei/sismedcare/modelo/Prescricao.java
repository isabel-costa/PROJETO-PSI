package pt.ipleiria.estg.dei.sismedcare.modelo;

import java.util.List;

public class Prescricao {
    private int id;
    private String dataPrescricao;
    private String nomeMedico;
    private List<PrescricaoMedicamento> medicamentos;

    public Prescricao(int id, String dataPrescricao, String nomeMedico, List<PrescricaoMedicamento> medicamentos) {
        this.id = id;
        this.dataPrescricao = dataPrescricao;
        this.nomeMedico = nomeMedico;
        this.medicamentos = medicamentos;
    }

    public int getId() { return id; }
    public String getDataPrescricao() { return dataPrescricao; }
    public String getNomeMedico() { return nomeMedico; }
    public List<PrescricaoMedicamento> getMedicamentos() { return medicamentos; }

    public void setId(int id) { this.id = id; }
}
