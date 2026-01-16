package pt.ipleiria.estg.dei.sismedcare;

import android.app.DatePickerDialog;
import android.app.TimePickerDialog;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.HashSet;
import java.util.List;
import java.util.Locale;
import java.util.Set;

import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class MarcarConsultasActivity extends AppCompatActivity {

    private Spinner spinnerTipo, spinnerProf;
    private EditText inputData, inputHora;
    private Button btnMarcar;
    private ImageView btnVoltar;

    private String medicoSelecionadoId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_marcar_consultas);

        spinnerTipo = findViewById(R.id.spinnerTipo);
        spinnerProf = findViewById(R.id.spinnerProf);
        inputData = findViewById(R.id.inputData);
        inputHora = findViewById(R.id.inputHora);
        btnMarcar = findViewById(R.id.btnMarcar);
        btnVoltar = findViewById(R.id.btnVoltar);

        btnVoltar.setOnClickListener(v -> finish());

        inputData.setOnClickListener(v -> mostrarDatePicker());
        inputHora.setOnClickListener(v -> mostrarTimePicker());
    }

    private void mostrarDatePicker() {
        Calendar c = Calendar.getInstance();
        DatePickerDialog dpd = new DatePickerDialog(this, (view, year, month, dayOfMonth) -> {
            inputData.setText(String.format(Locale.getDefault(), "%04d-%02d-%02d", year, month+1, dayOfMonth));
        }, c.get(Calendar.YEAR), c.get(Calendar.MONTH), c.get(Calendar.DAY_OF_MONTH));
        dpd.show();
    }

    private void mostrarTimePicker() {
        Calendar c = Calendar.getInstance();
        TimePickerDialog tpd = new TimePickerDialog(this, (view, hourOfDay, minute) -> {
            inputHora.setText(String.format(Locale.getDefault(), "%02d:%02d:00", hourOfDay, minute));
        }, c.get(Calendar.HOUR_OF_DAY), c.get(Calendar.MINUTE), true);
        tpd.show();
    }
}