import pandas as pd
import mysql.connector
from datetime import datetime, timedelta
from pandas import isna

df = pd.read_csv("final_donor_dataset.csv")

# CLEANING
df['availability'] = df['availability'].str.strip().str.capitalize()

def excel_to_date_safe(val):
    if val is None or isna(val):
        return None
    return datetime(1899, 12, 30) + timedelta(days=int(val))

df['created_at'] = df['created_at'].apply(excel_to_date_safe)
df['last_update'] = datetime.now()

# NaN → None
df = df.where(pd.notnull(df), None)

columns = [
    "donor_id",
    "name",
    "email",
    "contact_number",
    "city",
    "blood_group",
    "availability",
    "months_since_first_donation",
    "number_of_donation",
    "created_at",
    "usia",
    "berat_badan",
    "hb_level",
    "riwayat_penyakit",
    "jarak_ke_rs_km",
    "status_layak",
    "last_update"
]

data = df[columns].values.tolist()

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="blood_donation"
)

cursor = conn.cursor()

sql = """
INSERT INTO donors (
  donor_id, name, email, contact_number, city,
  blood_group, availability,
  months_since_first_donation, number_of_donation,
  created_at, usia, berat_badan, hb_level,
  riwayat_penyakit, jarak_ke_rs_km, status_layak, last_update
) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
"""

# cursor.executemany(sql, data)
# conn.commit()
for i, row in enumerate(data):
    try:
        cursor.execute(sql, row)
        conn.commit()
        print(f"Row {i} OK")
    except Exception as e:
        print("❌ ERROR DI ROW:", i)
        print("DATA:", row)
        print("MYSQL ERROR:", e)
        break

print(f"✅ {cursor.rowcount} data berhasil diimport")

cursor.close()
conn.close()
