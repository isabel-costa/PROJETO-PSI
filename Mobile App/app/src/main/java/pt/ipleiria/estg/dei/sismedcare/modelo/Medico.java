package pt.ipleiria.estg.dei.sismedcare.modelo;

import org.json.JSONException;
import org.json.JSONObject;

public class Medico {
    private String nome;
    private String especialidade;

    public Medico(String nome, String especialidade) {
        this.nome = nome;
        this.especialidade = especialidade;
    }

    public String getNome() {
        return nome;
    }
    public String getEspecialidade() {
        return especialidade;
    }

    public static Medico fromJson(JSONObject obj) throws JSONException {
        return new Medico(
                obj.getString("nome"),
                obj.getString("especialidade")
        );
    }
}
