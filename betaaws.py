from bs4 import BeautifulSoup
import re
import urllib3
from urllib.parse import urljoin
from concurrent.futures import ThreadPoolExecutor, as_completed
import requests
requests.packages.urllib3.disable_warnings()
import threading
from colorama import Fore, Style, init

init(autoreset=True)

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

aws_patterns = [
 r'"AKIA[A-Z0-9]{16}"',
]
aws_access_key_pattern = re.compile('|'.join(aws_patterns))
REQUEST_TIMEOUT = 10

def get_js_files(url):
    try:
        response = requests.get(url, verify=False, timeout=REQUEST_TIMEOUT)
        response.raise_for_status()
        soup = BeautifulSoup(response.text, 'html.parser')
        script_tags = soup.find_all('script', src=True)
        js_files = [tag['src'] for tag in script_tags if tag['src'].endswith('.js')]
        return js_files
    except requests.RequestException:
        return []


def scan_js_file(url, js_file_url):
    try:
        if not js_file_url.startswith('http'):
            js_file_url = urljoin(url, js_file_url)
        response = requests.get(js_file_url, verify=False, timeout=REQUEST_TIMEOUT)
        response.raise_for_status()
        if twilio_key_pattern.search(response.text):
            return js_file_url
        if aws_access_key_pattern.search(response.text):
            return js_file_url
    except requests.RequestException:
        return None
    return None

def scan_website(url, result_file_lock):
  if "://" in url:
        url = url
  else:
        url = "https://"+url
  if url.endswith('/'):
        url = url[:-1]
  try:
    r = requests.get(url, timeout=10)
    js_files = get_js_files(r.url)
    found = False
    for js_file in js_files:
        result = scan_js_file(r.url, js_file)
        if result:
            print(Fore.GREEN + f"AWS Access Key found in {result}")
            with result_file_lock:
                with open('Result/urls_with_aws_keys.txt', 'a') as output_file:
                    output_file.write(result + '\n')
            found = True
    if not found:
        print(Fore.RED + f"No AWS Access Key found in {r.url}" +Fore.WHITE)
  except:
      pass

def main():
    file_name = input("list: ").strip()
    num_threads = int(input("Thread: ").strip())
    
    try:
        with open(file_name, 'r') as file:
            urls = file.readlines()
    except FileNotFoundError:
        print(f"File {file_name} tidak ditemukan.")
        return
    
    urls = [url.strip() for url in urls if url.strip()]
    
    with ThreadPoolExecutor(max_workers=num_threads) as executor:
        futures = []
        result_file_lock = threading.Lock()
        for url in urls:
            futures.append(executor.submit(scan_website, url, result_file_lock))
        
        for future in as_completed(futures):
            future.result()

if __name__ == '__main__':
    main()