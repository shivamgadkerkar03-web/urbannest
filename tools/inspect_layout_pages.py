import win32com.client
import re
import os

word = win32com.client.Dispatch("Word.Application")
word.Visible = False
doc_path = os.path.abspath("Project_Report.docx")
doc = word.Documents.Open(doc_path)

def get_page(text):
    rng = doc.Content
    f = rng.Find
    f.Text = text
    f.Forward = True
    while f.Execute():
        if rng.Tables.Count == 0:
            return rng.Information(3) # wdActiveEndPageNumber
    return None

with open("tools/build_project_report.py", "r", encoding="utf-8") as file:
    content = file.read()

chapters = re.findall(r'ch_title\("([^"]+)",\s*"([^"]+)"\)', content)
secs = re.findall(r'sec_title\("([^"]+)"\)', content)
figs = re.findall(r'"(Figure\s+\d+\.\d+:[^"]+)"', content)

print("=== CHAPTER PAGES ===")
for ch_num, ch_name in chapters:
    p = get_page(f"CHAPTER {ch_num}")
    print(f"CHAPTER {ch_num} - {ch_name} -> Page {p}")

print("\n=== SECTION PAGES ===")
for s in secs:
    p = get_page(s)
    print(f"{s} -> Page {p}")

print("\n=== FIGURE PAGES ===")
for f_cap in figs:
    prefix = f_cap.split(":")[0]
    p = get_page(prefix)
    print(f"{prefix} -> Page {p}")

doc.Close(False)
word.Quit()

