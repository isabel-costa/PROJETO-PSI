package pt.ipleiria.estg.dei.sismedcare.modelo;

public class User {
    private int id;
    private String username;
    private String email;
    private int status;
    private long createdAt;
    private long updatedAt;
    private String authKey;

    public User(int id, String username, String email, int status, long createdAt, long updatedAt, String authKey) {
        this.id = id;
        this.username = username;
        this.email = email;
        this.status = status;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
        this.authKey = authKey;
    }

    public int getId() { return id; }

    public void setId(int id) { this.id = id; }
}
