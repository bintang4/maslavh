import os,sys


input_file_path = input("Please enter the path to your input file: ")

with open(input_file_path, 'r') as file:
    input_urls = file.read().splitlines()

filtered_domains = set()
for url in input_urls:
    domain = url.split("//")[-1].split("/")[0]
    if domain.endswith(".com"):
        filtered_domains.add(domain)

sorted_filtered_domains = sorted(filtered_domains)

print(sorted_filtered_domains)
