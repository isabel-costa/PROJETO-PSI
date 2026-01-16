package pt.ipleiria.estg.dei.sismedcare.modelo;

public class PrescricaoMedicamento {
    private String nome, posologia, frequencia, instrucoes;
    private int duracaoDias;

    public PrescricaoMedicamento(String nome, String posologia, String frequencia, int duracaoDias, String instrucoes) {
        this.nome = nome;
        this.posologia = posologia;
        this.frequencia = frequencia;
        this.duracaoDias = duracaoDias;
        this.instrucoes = instrucoes;
    }

    public String getNome() { return nome; }
    public String getPosologia() { return posologia; }
    public String getFrequencia() { return frequencia; }
    public int getDuracaoDias() { return duracaoDias; }
    public String getInstrucoes() { return instrucoes; }
}

