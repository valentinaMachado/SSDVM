from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.common.exceptions import TimeoutException
import time

print(" Iniciando prueba del proceso de compra del cliente...")

#  CONFIGURACIÓN
driver_path = r"C:\Users\Usuario\Documents\chromedriver\chromedriver-win64\chromedriver.exe"
base_url = "http://localhost/cliente/"  

correo = "tatiana@gmail.com"
contrasena = "12345"

#  INICIAR NAVEGADOR
service = Service(driver_path)
driver = webdriver.Chrome(service=service)
driver.maximize_window()

try:
    # 1️⃣ IR A LOGIN
    driver.get(base_url + "Iniciarsesioncli.html")
    print("➡️ Página de inicio de sesión abierta.")

    # 2️⃣ COMPLETAR LOGIN
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "Correo"))).send_keys(correo)
    driver.find_element(By.NAME, "Contrasena").send_keys(contrasena)
    driver.find_element(By.NAME, "Tipo_usuario").send_keys("CLIENTE")
    driver.find_element(By.ID, "btniniciarsesion").click()
    print("✅ Login enviado.")

    # 3️⃣ ESPERAR MENÚ
    WebDriverWait(driver, 10).until(EC.url_contains("menuCli.php"))
    print("🎯 Redirigido al menú del cliente.")

    # 4️⃣ AGREGAR PRODUCTO AL CARRITO
    driver.get(base_url + "menuCli.php")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, ".boton1")))
    botones = driver.find_elements(By.CSS_SELECTOR, ".boton1")

    if not botones:
        raise Exception("⚠️ No se encontraron botones .boton1 en menuCli.php")

    boton_agregar = botones[0]
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton_agregar)
    time.sleep(1)
    driver.execute_script("arguments[0].click();", boton_agregar)
    print("🛒 Producto agregado al carrito correctamente.")

    # 5️⃣ IR A ORDENAR
    driver.get(base_url + "ordenCli.html")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "nombre")))

    driver.find_element(By.NAME, "nombre").send_keys("tatiana")
    driver.find_element(By.NAME, "email").send_keys("tatiana@gmail.com")
    driver.find_element(By.NAME, "telefono").send_keys("3001234567")
    driver.find_element(By.NAME, "direccion").send_keys("Calle Falsa 123")
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    print("📦 Datos de orden enviados.")

    # 6️⃣ ESPERAR PAGO
    WebDriverWait(driver, 10).until(EC.url_contains("pago.php"))
    print("💳 Página de pago cargada.")

    # 7️⃣ PROCESAR PAGO
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "method"))).send_keys("cash")
    driver.find_element(By.CSS_SELECTOR, "input[type='submit']").click()
    print("💰 Pago procesado.")

    # 8️⃣ CONFIRMAR PANTALLA DE AGRADECIMIENTO
    WebDriverWait(driver, 10).until(EC.url_contains("gracias.php"))
    print("🎉 Compra finalizada correctamente. Página de agradecimiento mostrada.")

except TimeoutException as e:
    print("⏰ Error: se agotó el tiempo de espera.", e)
except Exception as e:
    print("❌ Error general:", e)
finally:
    time.sleep(4)
    driver.quit()
    print("🚪 Navegador cerrado.")