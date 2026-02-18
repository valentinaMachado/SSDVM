from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.common.exceptions import TimeoutException
import time

print("🧪 Iniciando prueba de carrito...")

# Ruta local al chromedriver
driver_path = r"C:\Users\Usuario\Documents\chromedriver\chromedriver-win64\chromedriver.exe"

# Configuración del navegador
service = Service(driver_path)
options = webdriver.ChromeOptions()
options.add_argument("--start-maximized")

driver = webdriver.Chrome(service=service, options=options)
wait = WebDriverWait(driver, 10)

try:
    print("✅ Iniciando sesión...")

    # === 1️⃣ Ingresar al login ===
    driver.get("http://localhost/cliente/Iniciarsesioncli.html")

    correo = wait.until(EC.presence_of_element_located((By.NAME, "Correo")))
    contrasena = driver.find_element(By.NAME, "Contrasena")
    tipo_usuario = driver.find_element(By.NAME, "Tipo_usuario")

    correo.send_keys("tatiana@gmail.com")
    contrasena.send_keys("12345")
    tipo_usuario.send_keys("cliente")

    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    # Esperar redirección al menú
    wait.until(EC.url_contains("menuCli.php"))
    print("✅ Se cargó el menú del cliente.")

    # === 2️⃣ Verificar que el menú y los productos cargaron ===
    titulo = wait.until(EC.presence_of_element_located((By.XPATH, "//h3[contains(text(),'MENÚ')]")))
    print("✅ Título del menú visible.")

    productos = driver.find_elements(By.CLASS_NAME, "boton1")
    print(f"🔍 Se encontraron {len(productos)} botones de 'Agregar al carrito'.")

    # === 3️⃣ Agregar un producto al carrito ===
    if len(productos) > 0:
        boton_agregar = productos[0]

        # Desplazarse al botón para asegurarse de que esté visible
        driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton_agregar)
        time.sleep(1)

        #  NUEVA LÍNEA — clic real para disparar el evento onclick del JS
        driver.execute_script("""
            arguments[0].dispatchEvent(new MouseEvent('click', {
                bubbles: true,
                cancelable: true,
                view: window
            }));
        """, boton_agregar)
        print("🛒 Producto agregado al carrito (evento real disparado).")

        # Esperar a que el carrito y la BD se actualicen
        time.sleep(3)

        # Abrir el carrito visual
        driver.find_element(By.ID, "cart-btn").click()
        print("🛍️ Abriendo carrito visual...")

        time.sleep(2)
        carrito_items = driver.find_elements(By.CSS_SELECTOR, ".shopping-cart .box")
        if len(carrito_items) > 0:
            print(f"✅ El carrito muestra {len(carrito_items)} producto(s).")
        else:
            print("⚠️ El carrito aún se ve vacío en el frontend.")
    else:
        print("❌ No se encontraron botones de agregar al carrito.")

except TimeoutException as e:
    print("❌ Error: tiempo de espera agotado al buscar un elemento.")
    print(e)
except Exception as e:
    print("❌ Error inesperado durante la prueba:", e)
finally:
    print("\n🔚 Prueba finalizada.")
    time.sleep(3)
    try:
        driver.quit()
    except:
        pass  # ✅ Evita mostrar el mensaje rojo al cerrar Chrome