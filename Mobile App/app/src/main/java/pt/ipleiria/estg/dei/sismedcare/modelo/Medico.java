package pt.ipleiria.estg.dei.sismedcare.modelo;

import org.json.JSONException;
import org.json.JSONObject;

public class Medico {
    private String nome;
    private String cedulaNumero;

    public Medico(String nome, String cedulaNumero) {
        this.nome = nome;
        this.cedulaNumero = cedulaNumero;
    }

    public String getNome() {
        return nome;
    }

    public String getCedulaNumero() {
        return cedulaNumero;
    }

    public static Medico fromJson(JSONObject obj) throws JSONException {
        return new Medico(
                obj.getString("nome"),
                obj.getString("cedula_numero")
        );
    }
}
