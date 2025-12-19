import joblib
import pickle
import pandas as pd
import numpy as np
import sys
import json

import os

# Load model dan encoders
# Gunakan absolute path agar bisa dipanggil dari folder manapun (misal dari API PHP)
current_dir = os.path.dirname(os.path.abspath(__file__))

model = joblib.load(os.path.join(current_dir, 'blood_donor_model.pkl'))

with open(os.path.join(current_dir, 'encoders_final.pkl'), 'rb') as f:
    encoders = pickle.load(f)

with open(os.path.join(current_dir, 'feature_names.pkl'), 'rb') as f:
    feature_names = pickle.load(f)

def predict_donor_eligibility(donor_data):
    """
    Memprediksi kelayakan donor berdasarkan data input
    """
    try:
        # Strict Rule 1: Berat Badan < 45 kg
        if int(donor_data.get('berat_badan', 0)) < 45:
             return {
                'status_layak': 0,
                'probability': 0.0,
                'features_used': ['Berat Badan < 45kg (Strict Rule)']
            }

        # Strict Rule 2: Usia (17 - 60 tahun) -> Tolak jika < 17 atau > 60
        usia = int(donor_data.get('usia', 0))
        if usia < 17 or usia > 60:
             return {
                'status_layak': 0,
                'probability': 0.0,
                'features_used': [f'Usia {usia} tahun (Rentang valid: 17-60)']
            }

        # Strict Rule 3: Interval Donor (< 2 bulan) -> Ditangguhkan (Status 2)
        last_donor_months = int(donor_data.get('months_since_first_donation', 0)) # Using this field as 'months_since_last' based on UI label
        # Note: UI field id is 'months_since_first_donation' but label says 'Terakhir Donor'. 
        # Assuming the input represents "Bulan sejak donor terakhir".
        if last_donor_months < 2 and last_donor_months != 0: # 0 means never donated, which is fine
             return {
                'status_layak': 2,
                'probability': 0.0,
                'features_used': ['Jarak donor < 2 bulan (Ditangguhkan)']
            }

        # Strict Rule 4: HB Level (Gender Specific)
        gender = donor_data.get('gender', 'L')
        hb = float(donor_data.get('hb_level', 0))
        
        # 4a. HB Sangat Rendah (< 10.0) -> Tolak (0)
        if hb < 10.0:
            return {'status_layak': 0, 'probability': 0.0, 'features_used': ['HB Sangat Rendah < 10.0 (Anemia Berat)']}
            
        # 4b. HB Tinggi (> 17.0) -> Tolak (0)
        if hb > 17.0:
            return {'status_layak': 0, 'probability': 0.0, 'features_used': ['HB Tinggi > 17.0 (Darah Kental)']}
            
        # 4c. HB Rendah / Ditangguhkan (10.0 - Threshold) -> Status 2
        # Threshold: Pria 13.5, Wanita 12.5
        min_hb = 13.5 if gender == 'L' else 12.5
        if 10.0 <= hb < min_hb:
             return {
                'status_layak': 2,
                'probability': 0.0, 
                'features_used': [f'HB Rendah {hb} (Butuh {min_hb}) - Ditangguhkan']
            }
        # Strict Rule: Langsung tolak jika ada penyakit tertentu (Permintaan User)
        restricted_diseases = ['Hipertensi', 'Diabetes', 'Jantung', 'Hepatitis']
        if donor_data.get('riwayat_penyakit') in restricted_diseases:
            return {
                'status_layak': 0,
                'probability': 0.0,
                'features_used': ['Riwayat Penyakit (Strict Rule)']
            }
        # Convert input ke DataFrame
        df = pd.DataFrame([donor_data])
        
        # Encode categorical features
        # Note: Keys in pickle file have '_encoder' suffix
        df['blood_group_encoded'] = encoders['blood_encoder'].transform([donor_data['blood_group']])[0]
        df['riwayat_penyakit_encoded'] = encoders['penyakit_encoder'].transform([donor_data['riwayat_penyakit']])[0]
        
        # One-hot encoding for penyakit
        penyakit_mapping = {
            'Tidak': 0, 'Hipertensi': 0, 'Diabetes': 0,
            'Jantung': 0, 'Hepatitis': 0
        }
        penyakit_mapping[f"penyakit_{donor_data['riwayat_penyakit']}"] = 1
        
        # Add one-hot encoded columns
        for penyakit in ['Diabetes', 'Hepatitis', 'Hipertensi', 'Jantung', 'Tidak']:
            df[f'penyakit_{penyakit}'] = penyakit_mapping.get(f'penyakit_{penyakit}', 0)
        
        # Calculate engineered features
        df['donor_berpengalaman'] = 1 if donor_data['number_of_donation'] > 5 else 0
        df['frekuensi_donor'] = donor_data['number_of_donation'] / max(donor_data['months_since_first_donation'], 1)
        df['health_score'] = (donor_data['hb_level'] / 17 * 4) + \
                            (3 if donor_data['berat_badan'] >= 50 else 1) + \
                            (3 if donor_data['riwayat_penyakit'] == 'Tidak' else 0)
        
        # Encode kategori features
        hb_bins = [11, 12.0, 13.0, 14.0, 17]
        hb_labels = ['sangat_rendah', 'rendah', 'normal', 'tinggi']
        df['kategori_hb_encoded'] = pd.cut([donor_data['hb_level']], bins=hb_bins, labels=hb_labels)[0]
        df['kategori_hb_encoded'] = encoders['kategori_hb_encoder'].transform([df['kategori_hb_encoded'].iloc[0]])[0]
        
        usia_bins = [17, 30, 45, 65]
        usia_labels = ['muda', 'dewasa', 'tua']
        df['usia_kategori_encoded'] = pd.cut([donor_data['usia']], bins=usia_bins, labels=usia_labels)[0]
        df['usia_kategori_encoded'] = encoders['usia_kategori_encoder'].transform([df['usia_kategori_encoded'].iloc[0]])[0]
        
        # Select features sesuai dengan model training (Verified from model.feature_names_in_)
        # model expects: ['hb_level' 'kategori_hb_encoded' 'health_score' 'penyakit_Jantung' 'riwayat_penyakit_encoded' 'penyakit_Tidak' 'frekuensi_donor' 'penyakit_Hipertensi' 'jarak_ke_rs_km' 'berat_badan']
        correct_features = ['hb_level', 'kategori_hb_encoded', 'health_score', 'penyakit_Jantung', 
                           'riwayat_penyakit_encoded', 'penyakit_Tidak', 'frekuensi_donor', 
                           'penyakit_Hipertensi', 'jarak_ke_rs_km', 'berat_badan']
        
        # Ensure all columns exist (fill 0 if missing from one-hot)
        for col in correct_features:
            if col not in df.columns:
                df[col] = 0
                
        X = df[correct_features]
        
        # Predict
        prediction = model.predict(X)[0]
        probability = model.predict_proba(X)[0][1]
        
        return {
            'status_layak': int(prediction),
            'probability': float(probability),
            'features_used': X.columns.tolist()
        }
        
    except Exception as e:
        return {'error': str(e)}

# Untuk API endpoint
if __name__ == "__main__":
    # Read JSON input
    input_data = json.loads(sys.stdin.read())
    
    # Predict
    result = predict_donor_eligibility(input_data)
    
    # Output JSON
    print(json.dumps(result))