from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.webdriver.chrome.service import Service
import time

# Ruta del chromedriver
service = Service("C:/Users/Usuario/Documents/chromedriver/chromedriver-win64/chromedriver.exe")

# Configurar navegador
driver = webdriver.Chrome(service=service)
driver.maximize_window()

# URL del formulario de inicio de sesión
driver.get("http://localhost/cliente/iniciarsesioncli.html")

# Esperar a que cargue bien
time.sleep(2)

# --- Paso 1: Llenar campos de inicio de sesión ---
driver.find_element(By.NAME, "Correo").send_keys("tatiana@gmail.com") 
time.sleep(1)
driver.find_element(By.NAME, "Contrasena").send_keys("12345")     
time.sleep(1)

# Seleccionar tipo de usuario
select_tipo = Select(driver.find_element(By.NAME, "Tipo_usuario"))
select_tipo.select_by_visible_text("CLIENTE")
time.sleep(1)

# Activar los checkboxes
driver.find_element(By.ID, "validardatos").click()
time.sleep(0.5)
driver.find_element(By.ID, "terminos").click()
time.sleep(0.5)

# --- Paso 2: Enviar formulario ---
driver.find_element(By.ID, "btniniciarsesion").click()

# Esperar para ver el resultado 
time.sleep(4)

# --- Paso 3: Comprobación visual o validación ---
print(" Prueba de inicio de sesión ejecutada correctamente")

# Cerrar navegador
driver.quit()