package pt.ipleiria.estg.dei.sismedcare.modelo;

public class Medicamento {
    private String nome;

    public Medicamento(String nome) {
        this.nome = nome;
    }

    public String getNome() {
        return nome;
    }

    public void setNome(String nome) {
        this.nome = nome;
    }
}
