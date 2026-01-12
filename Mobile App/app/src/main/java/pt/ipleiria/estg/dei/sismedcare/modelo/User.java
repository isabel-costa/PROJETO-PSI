package pt.ipleiria.estg.dei.sismedcare.modelo;

public class User {
    private int id;
    private String username;
    private String email;
    private int status;
    private long createdAt;
    private long updatedAt;

    // Se precisares do token do auth (Basic Auth)
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

    // Getters e Setters
    public int getId() { return id; }
    public String getUsername() { return username; }
    public String getEmail() { return email; }
    public int getStatus() { return status; }
    public long getCreatedAt() { return createdAt; }
    public long getUpdatedAt() { return updatedAt; }
    public String getAuthKey() { return authKey; }

    public void setId(int id) { this.id = id; }
    public void setUsername(String username) { this.username = username; }
    public void setEmail(String email) { this.email = email; }
    public void setStatus(int status) { this.status = status; }
    public void setCreatedAt(long createdAt) { this.createdAt = createdAt; }
    public void setUpdatedAt(long updatedAt) { this.updatedAt = updatedAt; }
    public void setAuthKey(String authKey) { this.authKey = authKey; }
}
