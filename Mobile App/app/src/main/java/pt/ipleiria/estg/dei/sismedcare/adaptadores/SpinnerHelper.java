package pt.ipleiria.estg.dei.sismedcare.adaptadores;

import android.content.Context;
import android.widget.ArrayAdapter;

public class SpinnerHelper {
    public static ArrayAdapter<String> criarAdapter(Context context, String[] items) {
        return new ArrayAdapter<>(context, android.R.layout.simple_spinner_item, items);
    }
}
