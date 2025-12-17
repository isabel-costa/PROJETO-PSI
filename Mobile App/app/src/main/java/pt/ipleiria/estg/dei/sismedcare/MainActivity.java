package pt.ipleiria.estg.dei.sismedcare;

import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;

import com.google.android.material.bottomnavigation.BottomNavigationView;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        BottomNavigationView bottomNav = findViewById(R.id.menu_navegacao_fragments);

        // Fragment inicial
        getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, new HomepageFragment())
                .commit();

        bottomNav.setOnItemSelectedListener(item -> {
            Fragment selectedFragment = null;

            int itemId = item.getItemId();

            if (itemId == R.id.menu_homepage) {
                selectedFragment = new HomepageFragment();
            } else if (itemId == R.id.menu_consultas) {
                selectedFragment = new ConsultasFragment();
            } else if (itemId == R.id.menu_prescricoes) {
                selectedFragment = new PrescricoesFragment();
            }

            if (selectedFragment != null) {
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, selectedFragment)
                        .commit();
            }

            return true;
        });

    }
}