from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time

# Ruta a  chromedriver
service = Service(r"C:\\Users\\Usuario\\Documents\\chromedriver\\chromedriver-win64\\chromedriver.exe")
driver = webdriver.Chrome(service=service)

try:
    driver.get("http://localhost/cliente/menuCli.php")
    driver.maximize_window()
    wait = WebDriverWait(driver, 15)

    # 1️⃣ Verificar carga de la página
    assert "Sistema de Servicio a Domicilio" in driver.title
    print("✅ Página del menú cargó correctamente.")

    # 2️⃣ Título principal
    titulo = wait.until(EC.visibility_of_element_located((By.XPATH, "//h3[contains(text(),'MENÚ CHARCUTERÍA CANAÁN')]")))
    print("✅ Título principal visible.")

    # 3️⃣ Contar productos
    productos = driver.find_elements(By.CLASS_NAME, "swiper-slide")
    assert len(productos) > 0
    print(f"✅ Se encontraron {len(productos)} productos en el menú.")

    # 4️⃣ Buscar botón 'Agregar al carrito' sin importar si está oculto
    botones = driver.find_elements(By.XPATH, "//button[contains(text(),'AGREGAR AL CARRITO')]")

    if len(botones) > 0:
        boton = botones[0]
        driver.execute_script("arguments[0].scrollIntoView(true);", boton)
        time.sleep(1)
        driver.execute_script("arguments[0].click();", boton)
        print("✅ Botón 'Agregar al carrito' encontrado y clicado correctamente.")
    else:
        print("❌ No se encontró ningún botón con el texto 'AGREGAR AL CARRITO'.")

    time.sleep(2)

except Exception as e:
    print("❌ Error durante la prueba:", str(e))

finally:
    time.sleep(2)
    driver.quit()