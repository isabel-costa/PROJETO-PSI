package pt.ipleiria.estg.dei.sismedcare.modelo;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import java.util.ArrayList;
import java.util.List;

public class PrescricaoBDHelper extends SQLiteOpenHelper {

    private static final String DB_NOME = "db_prescricoes";
    private static final int DB_VERSION = 3;

    // Prescrições
    private static final String TABELA_PRESCRICOES = "prescricoes";
    private static final String COL_ID = "id";
    private static final String COL_DATA = "data_prescricao";
    private static final String COL_MEDICO = "medico";

    // Medicamentos
    private static final String TABELA_MEDICAMENTOS = "prescricao_medicamentos";
    private static final String COL_PRESCRICAO_ID = "prescricao_id";
    private static final String COL_NOME = "nome";
    private static final String COL_POSOLOGIA = "posologia";
    private static final String COL_FREQUENCIA = "frequencia";
    private static final String COL_DURACAO = "duracao_dias";
    private static final String COL_INSTRUCOES = "instrucoes";

    private SQLiteDatabase db;

    public PrescricaoBDHelper(Context context) {
        super(context, DB_NOME, null, DB_VERSION);
        db = getWritableDatabase();
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        // Tabela prescricoes
        db.execSQL(
                "CREATE TABLE " + TABELA_PRESCRICOES + " (" +
                        COL_ID + " INTEGER PRIMARY KEY, " +
                        COL_DATA + " TEXT, " +
                        COL_MEDICO + " TEXT)"
        );

        // tabela medicamentos
        db.execSQL(
                "CREATE TABLE " + TABELA_MEDICAMENTOS + " (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        COL_PRESCRICAO_ID + " INTEGER, " +
                        COL_NOME + " TEXT, " +
                        COL_POSOLOGIA + " TEXT, " +
                        COL_FREQUENCIA + " TEXT, " +
                        COL_DURACAO + " INTEGER, " +
                        COL_INSTRUCOES + " TEXT)"
        );
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABELA_MEDICAMENTOS);
        db.execSQL("DROP TABLE IF EXISTS " + TABELA_PRESCRICOES);
        onCreate(db);
    }

    // Prescrições

    public void guardarPrescricao(Prescricao p) {
        ContentValues values = new ContentValues();
        values.put(COL_ID, p.getId());
        values.put(COL_DATA, p.getDataPrescricao());
        values.put(COL_MEDICO, p.getNomeMedico());

        // Atualiza se existir, insere se não
        int rows = db.update(TABELA_PRESCRICOES, values, COL_ID + "=?", new String[]{String.valueOf(p.getId())});
        if (rows == 0) {
            db.insert(TABELA_PRESCRICOES, null, values);
        }

        // Guarda medicamentos
        guardarMedicamentos(p.getId(), p.getMedicamentos());
    }

    public Prescricao getPrescricaoById(int prescricaoId) {
        Cursor cursor = db.query(TABELA_PRESCRICOES,
                new String[]{COL_ID, COL_DATA, COL_MEDICO},
                COL_ID + "=?",
                new String[]{String.valueOf(prescricaoId)},
                null, null, null);

        Prescricao p = null;
        if (cursor.moveToFirst()) {
            p = new Prescricao(
                    cursor.getInt(0),
                    cursor.getString(1),
                    cursor.getString(2),
                    getMedicamentosPorPrescricao(prescricaoId)
            );
        }
        cursor.close();
        return p;
    }

    public List<Prescricao> getAllPrescricoes() {
        List<Prescricao> lista = new ArrayList<>();
        Cursor cursor = db.query(TABELA_PRESCRICOES,
                new String[]{COL_ID, COL_DATA, COL_MEDICO},
                null, null, null, null, COL_DATA + " DESC");

        if (cursor.moveToFirst()) {
            do {
                int id = cursor.getInt(0);
                Prescricao p = new Prescricao(
                        id,
                        cursor.getString(1),
                        cursor.getString(2),
                        getMedicamentosPorPrescricao(id)
                );
                lista.add(p);
            } while (cursor.moveToNext());
        }
        cursor.close();
        return lista;
    }

    // Medicamentos
    public void guardarMedicamentos(int prescricaoId, List<PrescricaoMedicamento> lista) {
        db.beginTransaction();
        try {
            // Remove antigos
            db.delete(TABELA_MEDICAMENTOS, COL_PRESCRICAO_ID + "=?", new String[]{String.valueOf(prescricaoId)});

            for (PrescricaoMedicamento m : lista) {
                ContentValues values = new ContentValues();
                values.put(COL_PRESCRICAO_ID, prescricaoId);
                values.put(COL_NOME, m.getNome());
                values.put(COL_POSOLOGIA, m.getPosologia());
                values.put(COL_FREQUENCIA, m.getFrequencia());
                values.put(COL_DURACAO, m.getDuracaoDias());
                values.put(COL_INSTRUCOES, m.getInstrucoes());
                db.insert(TABELA_MEDICAMENTOS, null, values);
            }
            db.setTransactionSuccessful();
        } finally {
            db.endTransaction();
        }
    }

    public List<PrescricaoMedicamento> getMedicamentosPorPrescricao(int prescricaoId) {
        List<PrescricaoMedicamento> lista = new ArrayList<>();
        Cursor cursor = db.query(TABELA_MEDICAMENTOS,
                new String[]{COL_NOME, COL_POSOLOGIA, COL_FREQUENCIA, COL_DURACAO, COL_INSTRUCOES},
                COL_PRESCRICAO_ID + "=?",
                new String[]{String.valueOf(prescricaoId)},
                null, null, null);

        if (cursor.moveToFirst()) {
            do {
                PrescricaoMedicamento pm = new PrescricaoMedicamento(
                        cursor.getString(0),
                        cursor.getString(1),
                        cursor.getString(2),
                        cursor.getInt(3),
                        cursor.getString(4)
                );
                lista.add(pm);
            } while (cursor.moveToNext());
        }
        cursor.close();
        return lista;
    }

    // Guarda várias prescrições de uma vez
    public void guardarPrescricoes(List<Prescricao> lista) {
        db.beginTransaction();
        try {
            for (Prescricao p : lista) {
                guardarPrescricao(p); // Já guarda medicamentos também
            }
            db.setTransactionSuccessful();
        } finally {
            db.endTransaction();
        }
    }
}
