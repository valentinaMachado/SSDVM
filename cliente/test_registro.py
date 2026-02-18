from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

# --- Configuración del ChromeDriver ---
service = Service("C:\\Users\\Usuario\\Documents\\chromedriver\\chromedriver-win64\\chromedriver.exe")
driver = webdriver.Chrome(service=service)

driver.get("http://localhost/cliente/Iniciarsesioncli.html")
driver.maximize_window()

wait = WebDriverWait(driver, 10)

# --- Función para escribir dentro del formulario de registro ---
def escribir_campo(nombre, valor):
    formulario = wait.until(EC.visibility_of_element_located((By.CLASS_NAME, "registrarse")))
    campo = formulario.find_element(By.NAME, nombre)
    driver.execute_script("arguments[0].scrollIntoView(true);", campo)
    time.sleep(0.5)
    campo.clear()
    campo.send_keys(valor)
    print(f"✔ Se ingresó {nombre}: {valor}")

# --- Paso 1: abrir el formulario de registro ---
try:
    btn_registro = wait.until(EC.element_to_be_clickable((By.ID, "btn-sign-up")))
    btn_registro.click()
    print("✔ Se hizo clic en el botón de registro.")
except:
    print("⚠ No se encontró el botón de registro, quizás ya está visible.")

# --- Paso 2: Esperar que el formulario de registro esté visible ---
formulario_registro = wait.until(EC.visibility_of_element_located((By.CLASS_NAME, "registrarse")))

# --- Paso 3: Llenar los campos del formulario ---
escribir_campo("Nombre", "tatiana")
escribir_campo("Correo", "tatiana@gmail.com")
escribir_campo("Password", "12345")

# --- Tipo de usuario (dentro del mismo formulario) ---
select_tipo = formulario_registro.find_element(By.ID, "TIPOUSUARIO")
driver.execute_script("arguments[0].scrollIntoView(true);", select_tipo)
time.sleep(0.5)
select_tipo.click()
select_tipo.send_keys("CLIENTE")
print("✔ Tipo de usuario seleccionado.")

# --- Marcar checkboxes ---
formulario_registro.find_element(By.ID, "validardatos").click()
formulario_registro.find_element(By.ID, "terminos").click()
print("✔ Checkboxes marcados.")

# --- Enviar formulario ---
boton_enviar = formulario_registro.find_element(By.ID, "btnregistrase")
driver.execute_script("arguments[0].scrollIntoView(true);", boton_enviar)
boton_enviar.click()
print("✔ Formulario enviado.")

# --- Esperar redirección ---
time.sleep(4)

# --- Verificar resultado ---
if "Iniciarsesioncli.html" in driver.current_url:
    print(" Prueba exitosa: redirige correctamente al inicio de sesión.")
else:
    print("❌ Prueba fallida. URL actual:", driver.current_url)

driver.quit()