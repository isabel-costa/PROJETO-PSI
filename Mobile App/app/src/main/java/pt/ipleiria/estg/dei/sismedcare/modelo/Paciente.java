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

    // Getters e Setters
    public int getId() { return id; }
    public int getUserId() { return userId; }
    public String getUsername() { return username; }
    public void setUsername(String username) { this.username = username; }
    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }
    public String getNomeCompleto() { return nomeCompleto; }
    public String getDataNascimento() { return dataNascimento; }
    public String getSexo() { return sexo; }
    public String getNumeroUtente() { return numeroUtente; }
    public String getTelemovel() { return telemovel; }
    public String getMorada() { return morada; }
    public float getAltura() { return altura; }
    public float getPeso() { return peso; }
    public String getAlergias() { return alergias; }
    public String getDoencasCronicas() { return doencasCronicas; }
    public String getDataRegisto() { return dataRegisto; }
    public User getUser() { return user; }

    public void setId(int id) { this.id = id; }
    public void setUserId(int userId) { this.userId = userId; }
    public void setNomeCompleto(String nomeCompleto) { this.nomeCompleto = nomeCompleto; }
    public void setDataNascimento(String dataNascimento) { this.dataNascimento = dataNascimento; }
    public void setSexo(String sexo) { this.sexo = sexo; }
    public void setNumeroUtente(String numeroUtente) { this.numeroUtente = numeroUtente; }
    public void setTelemovel(String telemovel) { this.telemovel = telemovel; }
    public void setMorada(String morada) { this.morada = morada; }
    public void setAltura(float altura) { this.altura = altura; }
    public void setPeso(float peso) { this.peso = peso; }
    public void setAlergias(String alergias) { this.alergias = alergias; }
    public void setDoencasCronicas(String doencasCronicas) { this.doencasCronicas = doencasCronicas; }
    public void setDataRegisto(String dataRegisto) { this.dataRegisto = dataRegisto; }
    public void setUser(User user) { this.user = user; }
}
