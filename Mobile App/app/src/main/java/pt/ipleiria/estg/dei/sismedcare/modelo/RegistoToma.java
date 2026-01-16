package pt.ipleiria.estg.dei.sismedcare.modelo;

public class RegistoToma {

    private String id;
    private String nomeMedicamento;
    private String quantidade;
    private String hora;
    private boolean foiTomado;

    public RegistoToma(String id, String nomeMedicamento,
                       String quantidade, String hora,
                       boolean foiTomado) {
        this.id = id;
        this.nomeMedicamento = nomeMedicamento;
        this.quantidade = quantidade;
        this.hora = hora;
        this.foiTomado = foiTomado;
    }

    public String getId() { return id; }
    public String getNomeMedicamento() { return nomeMedicamento; }
    public String getQuantidade() { return quantidade; }
    public String getHora() { return hora; }
    public boolean isFoiTomado() { return foiTomado; }

    public void setFoiTomado(boolean foiTomado) {
        this.foiTomado = foiTomado;
    }
}
