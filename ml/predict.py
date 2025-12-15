import joblib
import pickle
import pandas as pd
import numpy as np
import sys
import json

# Load model dan encoders
model = joblib.load('blood_donor_model.pkl')

with open('encoders_final.pkl', 'rb') as f:
    encoders = pickle.load(f)

with open('feature_names.pkl', 'rb') as f:
    feature_names = pickle.load(f)

def predict_donor_eligibility(donor_data):
    """
    Memprediksi kelayakan donor berdasarkan data input
    """
    try:
        # Convert input ke DataFrame
        df = pd.DataFrame([donor_data])
        
        # Encode categorical features
        df['blood_group_encoded'] = encoders['blood'].transform([donor_data['blood_group']])[0]
        df['riwayat_penyakit_encoded'] = encoders['penyakit'].transform([donor_data['riwayat_penyakit']])[0]
        
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
        df['kategori_hb_encoded'] = encoders['kategori_hb'].transform([df['kategori_hb_encoded'].iloc[0]])[0]
        
        usia_bins = [17, 30, 45, 65]
        usia_labels = ['muda', 'dewasa', 'tua']
        df['usia_kategori_encoded'] = pd.cut([donor_data['usia']], bins=usia_bins, labels=usia_labels)[0]
        df['usia_kategori_encoded'] = encoders['usia_kategori'].transform([df['usia_kategori_encoded'].iloc[0]])[0]
        
        # Select features sesuai dengan model training
        X = df[feature_names]
        
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