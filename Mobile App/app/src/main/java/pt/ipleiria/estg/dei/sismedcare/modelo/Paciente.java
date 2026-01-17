package pt.ipleiria.estg.dei.sismedcare.modelo;

public class Paciente {
    private int id;
    private int userId;
    private String username;
    private String email;
    private String nomeCompleto;
    private String dataNascimento; // YYYY-MM-DD
    private String sexo; // "M", "F", "O"
    private String numeroUtente;
    private String telemovel;
    private String morada;
    private float altura;
    private float peso;
    private String alergias;
    private String doencasCronicas;
    private String dataRegisto;

    private User user; // referência ao User associado

    public Paciente(int id, int userId, String nomeCompleto, String dataNascimento, String sexo,
                    String numeroUtente, String telemovel, String morada, float altura, float peso,
                    String alergias, String doencasCronicas, String dataRegisto, User user) {
        this.id = id;
        this.userId = userId;
        this.nomeCompleto = nomeCompleto;
        this.dataNascimento = dataNascimento;
        this.sexo = sexo;
        this.numeroUtente = numeroUtente;
        this.telemovel = telemovel;
        this.morada = morada;
        this.altura = altura;
        this.peso = peso;
        this.alergias = alergias;
        this.doencasCronicas = doencasCronicas;
        this.dataRegisto = dataRegisto;
        this.user = user;
    }

    public int getId() { return id; }
    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }
    public String getNomeCompleto() { return nomeCompleto; }
    public String getDataNascimento() { return dataNascimento; }
    public String getSexo() { return sexo; }
    public String getNumeroUtente() { return numeroUtente; }
    public String getTelemovel() { return telemovel; }
    public String getMorada() { return morada; }

    public void setId(int id) { this.id = id; }
}
