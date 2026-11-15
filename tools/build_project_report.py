import os
import sys
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.enum.section import WD_SECTION
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    tcPr.append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def set_cell_border(cell, **kwargs):
    tcPr = cell._tc.get_or_add_tcPr()
    tcBorders = OxmlElement('w:tcBorders')
    for edge in ('top', 'left', 'bottom', 'right', 'insideH', 'insideV'):
        edge_data = kwargs.get(edge)
        if edge_data:
            tag = f'w:{edge}'
            element = OxmlElement(tag)
            element.set(qn('w:val'), edge_data.get('val', 'single'))
            element.set(qn('w:sz'), str(edge_data.get('sz', 4)))
            element.set(qn('w:space'), str(edge_data.get('space', 0)))
            element.set(qn('w:color'), edge_data.get('color', 'CCCCCC'))
            tcBorders.append(element)
    tcPr.append(tcBorders)

def build_report():
    print("Initializing report build...")
    doc = Document()

    # Configure Styles
    normal = doc.styles['Normal']
    normal.font.name = 'Times New Roman'
    normal.font.size = Pt(12)
    normal.font.color.rgb = RGBColor(30, 41, 59)
    normal.paragraph_format.line_spacing = 1.5
    normal.paragraph_format.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    normal.paragraph_format.space_after = Pt(6)

    # Configure Heading 1 (Chapters)
    h1 = doc.styles['Heading 1']
    h1.font.name = 'Times New Roman'
    h1.font.size = Pt(16)
    h1.font.bold = True
    h1.font.color.rgb = RGBColor(30, 58, 138)
    h1.paragraph_format.alignment = WD_ALIGN_PARAGRAPH.CENTER
    h1.paragraph_format.space_before = Pt(18)
    h1.paragraph_format.space_after = Pt(6)
    h1.paragraph_format.keep_with_next = True

    # Configure Heading 2 (Sections)
    h2 = doc.styles['Heading 2']
    h2.font.name = 'Times New Roman'
    h2.font.size = Pt(13.5)
    h2.font.bold = True
    h2.font.color.rgb = RGBColor(30, 58, 138)
    h2.paragraph_format.alignment = WD_ALIGN_PARAGRAPH.LEFT
    h2.paragraph_format.space_before = Pt(14)
    h2.paragraph_format.space_after = Pt(4)
    h2.paragraph_format.keep_with_next = True

    # Configure Heading 3 (Subsections)
    h3 = doc.styles['Heading 3']
    h3.font.name = 'Times New Roman'
    h3.font.size = Pt(12)
    h3.font.bold = True
    h3.font.color.rgb = RGBColor(51, 65, 85)
    h3.paragraph_format.alignment = WD_ALIGN_PARAGRAPH.LEFT
    h3.paragraph_format.space_before = Pt(8)
    h3.paragraph_format.space_after = Pt(2)
    h3.paragraph_format.keep_with_next = True

    # Setup Section 1: Preliminary Pages (A4, Roman Numerals)
    sec1 = doc.sections[0]
    sec1.page_width = Inches(8.27)
    sec1.page_height = Inches(11.69)
    sec1.top_margin = Inches(1.0)
    sec1.bottom_margin = Inches(1.0)
    sec1.left_margin = Inches(1.25)
    sec1.right_margin = Inches(1.0)
    sec1.different_first_page_header_footer = True

    sectPr1 = sec1._sectPr
    pgNumType1 = OxmlElement('w:pgNumType')
    pgNumType1.set(qn('w:fmt'), 'roman')
    pgNumType1.set(qn('w:start'), '1')
    sectPr1.append(pgNumType1)

    # Section 1 Footer (Roman numerals i, ii, iii... Cover page is suppressed)
    ftr1 = sec1.footer
    fp1 = ftr1.paragraphs[0]
    fp1.alignment = WD_ALIGN_PARAGRAPH.CENTER
    frun1 = fp1.add_run()
    frun1.font.name = "Times New Roman"
    frun1.font.size = Pt(9.5)
    frun1.font.color.rgb = RGBColor(100, 116, 139)
    fld1 = OxmlElement('w:fldSimple')
    fld1.set(qn('w:instr'), 'PAGE')
    fp1._p.append(fld1)

    def p(text="", bold=False, italic=False, align=WD_ALIGN_PARAGRAPH.JUSTIFY, space_before=0, space_after=6, color=None, font_size=12):
        para = doc.add_paragraph()
        para.alignment = align
        para.paragraph_format.line_spacing = 1.5
        para.paragraph_format.space_before = Pt(space_before)
        para.paragraph_format.space_after = Pt(space_after)
        if text:
            run = para.add_run(text)
            run.font.name = 'Times New Roman'
            run.font.size = Pt(font_size)
            run.bold = bold
            run.italic = italic
            if color:
                run.font.color.rgb = color
        return para

    def ch_title(num_str, title_str):
        doc.add_page_break()
        ch_p = doc.add_paragraph(f"CHAPTER {num_str}", style='Heading 1')
        ch_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        
        t_p = doc.add_paragraph()
        t_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        t_p.paragraph_format.space_before = Pt(0)
        t_p.paragraph_format.space_after = Pt(18)
        t_p.paragraph_format.keep_with_next = True
        r = t_p.add_run(title_str.upper())
        r.font.name = 'Times New Roman'
        r.font.size = Pt(15)
        r.font.bold = True
        r.font.color.rgb = RGBColor(15, 23, 42)

    def sec_title(title_str):
        return doc.add_paragraph(title_str, style='Heading 2')

    def subsec_title(title_str):
        return doc.add_paragraph(title_str, style='Heading 3')

    def bullet(bold_prefix, text):
        para = doc.add_paragraph(style='List Bullet')
        para.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        para.paragraph_format.line_spacing = 1.35
        para.paragraph_format.space_before = Pt(2)
        para.paragraph_format.space_after = Pt(4)
        r1 = para.add_run(bold_prefix + " ")
        r1.font.name = 'Times New Roman'
        r1.font.size = Pt(11.5)
        r1.bold = True
        r2 = para.add_run(text)
        r2.font.name = 'Times New Roman'
        r2.font.size = Pt(11.5)

    def add_fig(img_path, caption_str, width_in=5.8):
        if os.path.exists(img_path):
            para = doc.add_paragraph()
            para.alignment = WD_ALIGN_PARAGRAPH.CENTER
            para.paragraph_format.space_before = Pt(10)
            para.paragraph_format.space_after = Pt(4)
            para.paragraph_format.keep_with_next = True
            run = para.add_run()
            run.add_picture(img_path, width=Inches(width_in))
            
            cp = doc.add_paragraph()
            cp.alignment = WD_ALIGN_PARAGRAPH.CENTER
            cp.paragraph_format.space_before = Pt(2)
            cp.paragraph_format.space_after = Pt(10)
            crun = cp.add_run(caption_str)
            crun.font.name = 'Times New Roman'
            crun.font.size = Pt(10.5)
            crun.bold = True
            crun.font.color.rgb = RGBColor(71, 85, 105)
        else:
            print(f"Warning: image {img_path} not found")

    def make_table(headers, rows, col_widths=None):
        table = doc.add_table(rows=len(rows) + 1, cols=len(headers))
        table.alignment = WD_TABLE_ALIGNMENT.CENTER
        hdr_row = table.rows[0]
        for idx, h_text in enumerate(headers):
            cell = hdr_row.cells[idx]
            cell.text = h_text
            set_cell_background(cell, "1e3a8a")
            set_cell_margins(cell, top=140, bottom=140, left=180, right=180)
            set_cell_border(cell, bottom=dict(sz=12, val='single', color='0f172a'))
            cp = cell.paragraphs[0]
            cp.alignment = WD_ALIGN_PARAGRAPH.LEFT
            for r in cp.runs:
                r.font.name = 'Times New Roman'
                r.font.size = Pt(10.5)
                r.bold = True
                r.font.color.rgb = RGBColor(255, 255, 255)
        
        for r_idx, r_data in enumerate(rows):
            row = table.rows[r_idx + 1]
            bg_hex = "f8fafc" if r_idx % 2 == 1 else "ffffff"
            for c_idx, val in enumerate(r_data):
                cell = row.cells[c_idx]
                cell.text = str(val)
                set_cell_background(cell, bg_hex)
                set_cell_margins(cell, top=100, bottom=100, left=160, right=160)
                set_cell_border(cell,
                    top=dict(sz=4, val='single', color='e2e8f0'),
                    bottom=dict(sz=4, val='single', color='e2e8f0'),
                    left=dict(sz=4, val='single', color='e2e8f0'),
                    right=dict(sz=4, val='single', color='e2e8f0')
                )
                cp = cell.paragraphs[0]
                cp.alignment = WD_ALIGN_PARAGRAPH.LEFT
                for r in cp.runs:
                    r.font.name = 'Times New Roman'
                    r.font.size = Pt(10)
                    r.font.color.rgb = RGBColor(51, 65, 85)

        if col_widths:
            for row in table.rows:
                for c_idx, w in enumerate(col_widths):
                    row.cells[c_idx].width = Inches(w)
        
        p("", space_before=0, space_after=6)
        return table

    def add_code_block(title_comment, code_lines):
        tbl = doc.add_table(rows=1, cols=1)
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        cell = tbl.cell(0, 0)
        set_cell_background(cell, "f8fafc")
        set_cell_margins(cell, top=100, bottom=100, left=180, right=180)
        set_cell_border(cell,
            top=dict(sz=4, val='single', color='cbd5e1'),
            bottom=dict(sz=4, val='single', color='cbd5e1'),
            left=dict(sz=16, val='single', color='1e3a8a'),
            right=dict(sz=4, val='single', color='cbd5e1')
        )
        cell.width = Inches(6.0)
        
        p_t = cell.paragraphs[0]
        p_t.paragraph_format.line_spacing = 1.15
        p_t.paragraph_format.space_before = Pt(2)
        p_t.paragraph_format.space_after = Pt(4)
        rt = p_t.add_run(title_comment)
        rt.font.name = 'Consolas'
        rt.font.size = Pt(9.5)
        rt.font.bold = True
        rt.font.color.rgb = RGBColor(71, 85, 105)
        
        p_c = cell.add_paragraph()
        p_c.paragraph_format.line_spacing = 1.15
        p_c.paragraph_format.space_before = Pt(0)
        p_c.paragraph_format.space_after = Pt(2)
        rc = p_c.add_run("\n".join(code_lines))
        rc.font.name = 'Consolas'
        rc.font.size = Pt(9)
        rc.font.color.rgb = RGBColor(15, 23, 42)
        
        p("", space_before=0, space_after=6)

    # =========================================================================
    # SECTION 1: PRELIMINARY PAGES
    # =========================================================================

    # --- 1. COVER PAGE ---
    p("PROJECT REPORT", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=24, space_after=8, color=RGBColor(30, 58, 138), font_size=18)
    p("URBANNEST", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=6, space_after=6, color=RGBColor(15, 23, 42), font_size=22)
    p("INTELLIGENT ACCOMMODATION DISCOVERY, ALGORITHMIC ROOMMATE MATCHING & CAMPUS MARKETPLACE PLATFORM", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=14, color=RGBColor(71, 85, 105), font_size=13)
    p("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=24, color=RGBColor(203, 213, 225))
    
    p("Submitted in partial fulfilment of the requirements for the degree of", italic=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=10, space_after=4, font_size=11.5)
    p("[Degree / Programme Name]", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=2, space_after=28, color=RGBColor(30, 58, 138), font_size=14)

    p("Submitted by:", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=10, space_after=2, font_size=11.5)
    p("[Student Name]", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=2, color=RGBColor(15, 23, 42), font_size=13)
    p("Register Number: [Register Number]", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=24, font_size=11.5)

    p("Under the guidance of:", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=10, space_after=2, font_size=11.5)
    p("[Project Guide Name]", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=2, color=RGBColor(15, 23, 42), font_size=13)
    p("[Guide Designation / Department]", italic=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=28, font_size=11)

    p("[Department Name]", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=14, space_after=2, color=RGBColor(30, 58, 138), font_size=13)
    p("[College / University Name]", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=2, font_size=14)
    p("[Academic Year: 2025 – 2026]", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=12, font_size=11)

    # --- 2. CERTIFICATE ---
    doc.add_page_break()
    p("CERTIFICATE", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=20, space_after=18, color=RGBColor(30, 58, 138), font_size=16)
    p("This is to certify that the project report entitled “UrbanNest – Intelligent Accommodation Discovery, Algorithmic Roommate Matching & Campus Marketplace Platform” is a bona fide record of the academic project work carried out by [Student Name], bearing Register Number [Register Number], in partial fulfilment of the requirements for the award of the degree of [Degree / Programme Name] during the academic year [Academic Year].", space_before=6, space_after=14)
    p("The project report covers the comprehensive problem investigation, system requirements analysis, relational architectural design, secure implementation, algorithmic compatibility development, and quality assurance testing of a full-stack web application designed to solve student and migrant housing, roommate conflicts, and campus thrift exchange.", space_before=4, space_after=14)
    p("To the best of my knowledge, the matter embodied in this report has not been submitted to any other University or Institution for the award of any degree or diploma.", space_before=4, space_after=36)

    # Signature blocks
    sig_table = doc.add_table(rows=2, cols=3)
    sig_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    for r in sig_table.rows:
        for c in r.cells:
            c.width = Inches(2.0)
    
    r0 = sig_table.rows[0]
    r0.cells[0].paragraphs[0].add_run("PROJECT GUIDE\n\n\n______________________\nName: [Guide Name]\nDesignation: [Designation]").font.size = Pt(10)
    r0.cells[1].paragraphs[0].add_run("HEAD OF DEPARTMENT\n\n\n______________________\nName: [HOD Name]\nDepartment: [Department]").font.size = Pt(10)
    r0.cells[2].paragraphs[0].add_run("EXAMINERS\n\n\n1. ___________________\n\n2. ___________________").font.size = Pt(10)
    
    p("", space_before=24, space_after=6)
    p("Date: ____________________                                                   Place: ____________________", bold=True, space_before=16, space_after=6)

    # --- 3. CANDIDATE DECLARATION ---
    doc.add_page_break()
    p("CANDIDATE DECLARATION", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=20, space_after=18, color=RGBColor(30, 58, 138), font_size=16)
    p("I, [Student Name], bearing Register Number [Register Number], student of [Degree / Programme Name] in the [Department Name], [College / University Name], hereby declare that the project entitled “UrbanNest – Intelligent Accommodation Discovery, Algorithmic Roommate Matching & Campus Marketplace Platform” submitted by me in partial fulfilment of the requirements for the award of the degree, is an authentic record of original academic project work carried out during the academic year [Academic Year] under the supervision and guidance of [Project Guide Name], [Guide Designation / Department].", space_before=6, space_after=14)
    p("I further declare that this project work has not formed the basis for the award of any other degree, diploma, fellowship, or other similar academic recognition to any candidate of any other University or Institution.", space_before=4, space_after=28)
    p("The technical analyses, code snippets, architectural diagrams, and application screenshots included in this report accurately reflect the software engineering artefact developed and evaluated during the project tenure.", space_before=4, space_after=36)
    p("[Student Name]\nRegister Number: [Register Number]\n[Department Name]\n[College / University Name]", bold=True, align=WD_ALIGN_PARAGRAPH.RIGHT, space_before=24, space_after=12)
    p("Date: ____________________                                                   Place: ____________________", bold=True, space_before=16, space_after=6)

    # --- 4. ACKNOWLEDGMENT ---
    doc.add_page_break()
    p("ACKNOWLEDGMENT", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=20, space_after=18, color=RGBColor(30, 58, 138), font_size=16)
    p("I take this opportunity to express my profound gratitude and deep respect to my project guide, [Project Guide Name / Designation], for their invaluable guidance, constructive critique, and continuous encouragement throughout the conception, architectural design, implementation, and documentation of the UrbanNest platform. Their insightful technical advice and rigorous standards were pivotal in overcoming complex engineering challenges in database normalization, algorithmic roommate matching, and asynchronous communication.", space_before=6, space_after=12)
    p("I extend my sincere appreciation to [HOD Name], Head of the Department of [Department Name], and our respected Principal / Dean, for providing excellent laboratory infrastructure, development computing environments, and institutional support necessary for the successful completion of this project.", space_before=4, space_after=12)
    p("I also express my heartfelt thanks to all the faculty members of the department who enriched my academic foundation in database management, web engineering, software testing, and object-oriented systems. Special thanks to the open-source community behind PHP, MySQL, Apache, Bootstrap 5, jQuery, and Google Font APIs whose robust tools and libraries made the rapid development of this solution possible.", space_before=4, space_after=12)
    p("Finally, I am eternally indebted to my family and friends for their enduring patience, moral encouragement, and selfless support during every phase of this academic endeavour.", space_before=4, space_after=28)
    p("[Student Name]\nRegister No: [Register Number]", bold=True, align=WD_ALIGN_PARAGRAPH.RIGHT, space_before=12, space_after=6)

    # --- 5. ABSTRACT ---
    doc.add_page_break()
    p("ABSTRACT", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=20, space_after=18, color=RGBColor(30, 58, 138), font_size=16)
    p("Urban migration among students and young working professionals in metropolitan areas has introduced acute challenges in securing safe, affordable, and transparent paying guest (PG) accommodations. Traditional manual methodologies—such as broker syndicates, unstructured social media forums, and unverified local advertisements—suffer from severe information asymmetry, extortionate brokerage commissions, fraudulent property listings, and persistent interpersonal conflicts arising from lifestyle mismatches among randomly paired roommates. Furthermore, when tenants relocate, discarding usable co-living furniture and appliances creates substantial financial loss and environmental waste.", space_before=6, space_after=10)
    p("To resolve these interconnected dilemmas, this project presents UrbanNest, an integrated, full-stack, three-tiered web platform delivering verified accommodation discovery, algorithmic roommate lifestyle compatibility matching, and a dedicated peer-to-peer campus thrift marketplace (Urban Nation). The system is developed using PHP 8.2 and a normalized MySQL 8.0 relational database on Apache/XAMPP infrastructure, complemented by a responsive frontend built with HTML5, CSS3, Bootstrap 5, jQuery, and asynchronous AJAX communications.", space_before=4, space_after=10)
    p("The accommodation engine provides geo-filtered PG discovery (by state, city, and rent bounds), rich multimedia room galleries, amenity inventories, and an automated booking workflow connecting tenants directly to verified property owners without intermediaries. The core innovation of UrbanNest lies in its algorithmic roommate compatibility engine, which quantifies lifestyle alignment across critical co-living dimensions: cleanliness discipline, diurnal sleep cycles, smoking/drinking habits, dietary preferences, and professional occupancy status. The engine derives a normalized multi-attribute compatibility score (0%–100%) and renders ranked prospective matches alongside direct, asynchronous 1-on-1 real-time messaging with instantaneous polling and DOM deduplication.", space_before=4, space_after=10)
    p("Additionally, the integrated Urban Nation module establishes a circular campus marketplace allowing verified residents to list, discover, and negotiate pre-loved essentials (study desks, mattresses, mini-refrigerators) with zero intermediary overhead. Comprehensive quality assurance testing comprising unit, functional, boundary, integration, and security test cases was executed against the live application, confirming 100% route availability, robust SQL prepared-statement parameter binding, and responsive sub-second page loads. UrbanNest effectively transforms the urban relocation journey into a unified, transparent, and digitally coordinated experience.", space_before=4, space_after=14)
    p("Keywords: Accommodation Discovery, Roommate Matching Algorithm, Paying Guest System, Multi-Attribute Compatibility Scoring, Real-Time Web Chat, Campus Thrift Marketplace, PHP 8.2, MySQL Relational Database, Full-Stack Web Engineering.", italic=True, space_before=8, space_after=6)

    # --- 6. TABLE OF CONTENTS ---
    doc.add_page_break()
    p("TABLE OF CONTENTS", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=20, space_after=18, color=RGBColor(30, 58, 138), font_size=16)
    
    # Exact synchronized page numbers matching Section 2 pagination (Chapter 1 starting at Page 1)
    toc_data = [
        ("Abstract", "v"),
        ("List of Tables", "vii"),
        ("List of Figures", "viii"),
        ("CHAPTER 1 - INTRODUCTION", "1"),
        ("    1.1 Background of the Project", "1"),
        ("    1.2 Problem Statement", "1"),
        ("    1.3 Project Description and Core Modules", "2"),
        ("    1.4 Project Objectives", "3"),
        ("    1.5 Scope and Assumptions", "3"),
        ("CHAPTER 2 - SYSTEM STUDY", "5"),
        ("    2.1 Existing System Analysis", "5"),
        ("    2.2 Limitations of the Existing System", "5"),
        ("    2.3 Proposed System Architecture", "5"),
        ("    2.4 Advantages of the Proposed System", "6"),
        ("    2.5 Requirements Analysis (Functional & Non-Functional)", "7"),
        ("    2.6 Use Case Diagram", "8"),
        ("    2.7 Sequential Diagram", "8"),
        ("    2.8 Activity Diagram", "9"),
        ("CHAPTER 3 - SYSTEM CONFIGURATION", "11"),
        ("    3.1 Hardware Configuration", "11"),
        ("    3.2 Software Configuration & Tech Stack", "11"),
        ("    3.3 Development and Runtime Environment", "12"),
        ("CHAPTER 4 - SOFTWARE DESCRIPTION", "13"),
        ("    4.1 Overview of Frontend Technologies", "13"),
        ("    4.2 Overview of Backend Architecture", "13"),
        ("    4.3 Database Engine & Relational Modeling", "14"),
        ("    4.4 Main System Modules & Responsibilities", "14"),
        ("    4.5 Algorithmic Roommate Compatibility Scoring Logic", "15"),
        ("CHAPTER 5 - SYSTEM DESIGN", "17"),
        ("    5.1 System Architecture Design", "17"),
        ("    5.2 Input Design & Validation Rules", "17"),
        ("    5.3 Output Design & Reporting", "18"),
        ("    5.4 Database Design & Entity Relationship Modeling", "19"),
        ("    5.5 Key Database Entities and Data Dictionary", "20"),
        ("    5.6 Data Flow Design (DFD Level 1)", "22"),
        ("CHAPTER 6 - SOURCE CODE", "24"),
        ("    6.1 Important Source Files", "24"),
        ("    6.2 Core Scoring Logic", "25"),
        ("    6.3 Roommate Matching and Request Workflow", "26"),
        ("CHAPTER 7 - SYSTEM TESTING", "28"),
        ("    7.1 Testing Approach & Methodology", "28"),
        ("    7.2 Automated and Functional Test Summary", "28"),
        ("    7.3 Comprehensive Test Case Catalogue", "29"),
        ("    7.4 Testing Limitations & Evaluation", "30"),
        ("CHAPTER 8 - SYSTEM IMPLEMENTATION / DEPLOYMENT", "31"),
        ("    8.1 Prerequisites & System Dependencies", "31"),
        ("    8.2 Step-by-Step Installation & Execution Guide", "31"),
        ("    8.3 Production Readiness & Security Hardening Checklist", "32"),
        ("CHAPTER 9 - CONCLUSION AND FUTURE ENHANCEMENTS", "33"),
        ("    9.1 Conclusion", "33"),
        ("    9.2 Future Enhancements", "33"),
        ("CHAPTER 10 - APPENDICES (PROJECT SCREENSHOTS)", "34"),
        ("    10.1 Application Interface Screenshots (23 Figures)", "34"),
        ("CHAPTER 11 - REFERENCES / BIBLIOGRAPHY", "46"),
        ("    11.1 Academic and Technical Literature", "46"),
        ("    11.2 Frameworks, Specifications and Web Documentation", "46")
    ]
    make_table(["SECTION / SUBSECTION", "PAGE"], toc_data, col_widths=[5.5, 1.0])

    # --- 7. LIST OF TABLES ---
    doc.add_page_break()
    p("LIST OF TABLES", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=20, space_after=18, color=RGBColor(30, 58, 138), font_size=16)
    
    lot_data = [
        ("Table 1.1", "Core System Modules and Functional Responsibilities", "2"),
        ("Table 2.1", "Comparative Evaluation: Existing System vs. Proposed UrbanNest", "6"),
        ("Table 3.1", "Suggested Hardware Configuration (Development vs. Production)", "11"),
        ("Table 3.2", "Software Package and Framework Configuration", "11"),
        ("Table 4.1", "Backend Application Modules and Architectural Roles", "14"),
        ("Table 4.2", "Roommate Compatibility Scoring Matrix and Attribute Weights", "15"),
        ("Table 5.1", "Comprehensive Input Design and Field-Level Validation Rules", "17"),
        ("Table 5.2", "System Output Design, Display Media and Formats", "18"),
        ("Table 5.3", "Key Database Entities, Storage Engines and Primary Roles", "20"),
        ("Table 5.4", "Comprehensive Data Dictionary & Schema Specification", "20"),
        ("Table 6.1", "Source code map", "24"),
        ("Table 7.1", "Automated and Integration Test Execution Summary", "28"),
        ("Table 7.2", "Exhaustive System Test Case Catalogue and Verification Matrix", "29"),
        ("Table 8.1", "Production Deployment Readiness and Security Hardening Checklist", "32")
    ]
    make_table(["TABLE NO.", "TITLE", "PAGE"], lot_data, col_widths=[1.3, 4.7, 0.8])

    # --- 8. LIST OF FIGURES ---
    doc.add_page_break()
    p("LIST OF FIGURES", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=20, space_after=18, color=RGBColor(30, 58, 138), font_size=16)
    
    lof_data = [
        ("Figure 2.1", "Use Case Diagram - Multi-Role Actor Interactions", "8"),
        ("Figure 2.2", "Sequential Diagram - Roommate Matching and Connection Workflow", "8"),
        ("Figure 2.3", "Activity Diagram - End-to-End Accommodation & Roommate Workflow", "9"),
        ("Figure 5.1", "Three-Tier System Architecture Diagram", "17"),
        ("Figure 5.2", "Entity Relationship (ER) Diagram - Core Relational Schema", "19"),
        ("Figure 5.4", "Data Flow Diagram (DFD Level 1) - Information Processing Workflow", "22"),
        ("Figure 10.1", "Homepage Hero Section & Accommodation Search Banner", "34"),
        ("Figure 10.2", "Modern Centered Tenant Sign-In Interface", "35"),
        ("Figure 10.3", "New User Registration and Credential Enrollment Form", "35"),
        ("Figure 10.4", "PG Accommodation Directory with City & Budget Filters", "36"),
        ("Figure 10.5", "Detailed PG Accommodation Showcase with Gallery and Booking", "36"),
        ("Figure 10.6", "Urban Nation Peer-to-Peer Campus Thrift Store Portal", "37"),
        ("Figure 10.7", "Thrift Catalog Filter & Search with Direct Inquiry Channels", "37"),
        ("Figure 10.8", "Intelligent Roommate Discovery Hub and Dual-Pathway Options", "38"),
        ("Figure 10.9", "Ranked Roommate Matches Displaying Calculated Compatibility %", "38"),
        ("Figure 10.10", "Need-a-Room Preference Submission Form with Validation", "39"),
        ("Figure 10.11", "Have-a-Room Flatmate Listing Interface with Photo Uploads", "39"),
        ("Figure 10.12", "Real-Time 1-on-1 Asynchronous Roommate Chat Box", "40"),
        ("Figure 10.13", "Roommate Lifestyle Profile Management Interface", "40"),
        ("Figure 10.14", "Urban Nation Secondhand Item Posting and Cataloging Form", "41"),
        ("Figure 10.15", "PG Property Owner Authentication Gateway", "41"),
        ("Figure 10.16", "PG Property Owner Analytics Dashboard and Metrics", "42"),
        ("Figure 10.17", "Owner Inventory Management: PG Property Moderation", "42"),
        ("Figure 10.18", "Owner Tenant Booking Request Queue and Approval Controls", "43"),
        ("Figure 10.19", "System Administrator Authentication Interface", "43"),
        ("Figure 10.20", "Administrator Global Analytics and Governance Dashboard", "44"),
        ("Figure 10.21", "Administrator System-Wide PG Accommodation Moderation Table", "44"),
        ("Figure 10.22", "Administrator Owner KYC Verification and Approval Portal", "45"),
        ("Figure 10.23", "Administrator Master Booking Records and Oversight Panel", "45")
    ]
    make_table(["FIGURE NO.", "TITLE", "PAGE"], lof_data, col_widths=[1.3, 4.7, 0.8])

    # =========================================================================
    # SECTION 2: MAIN BODY (CHAPTERS 1 TO 11)
    # Starts at Page 1 in Arabic numerals
    # =========================================================================
    sec2 = doc.add_section(WD_SECTION.NEW_PAGE)
    sec2.header.is_linked_to_previous = False
    sec2.footer.is_linked_to_previous = False
    sec2.different_first_page_header_footer = False

    sectPr2 = sec2._sectPr
    pgNumType2 = OxmlElement('w:pgNumType')
    pgNumType2.set(qn('w:fmt'), 'decimal')
    pgNumType2.set(qn('w:start'), '1')
    sectPr2.append(pgNumType2)

    # Header for Section 2
    hdr2 = sec2.header
    hp2 = hdr2.paragraphs[0]
    hp2.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    hrun2 = hp2.add_run("URBANNEST: SMART ACCOMMODATION & ROOMMATE PLATFORM")
    hrun2.font.name = "Times New Roman"
    hrun2.font.size = Pt(8.5)
    hrun2.font.color.rgb = RGBColor(100, 116, 139)
    hrun2.italic = True

    # Footer for Section 2
    ftr2 = sec2.footer
    fp2 = ftr2.paragraphs[0]
    fp2.alignment = WD_ALIGN_PARAGRAPH.CENTER
    frun2 = fp2.add_run("Page ")
    frun2.font.name = "Times New Roman"
    frun2.font.size = Pt(9.5)
    frun2.font.color.rgb = RGBColor(100, 116, 139)
    fld2 = OxmlElement('w:fldSimple')
    fld2.set(qn('w:instr'), 'PAGE')
    fp2._p.append(fld2)

    # --- CHAPTER 1: INTRODUCTION ---
    ch_p = doc.add_paragraph("CHAPTER 1", style='Heading 1')
    ch_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    t_p = doc.add_paragraph()
    t_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    t_p.paragraph_format.space_before = Pt(0)
    t_p.paragraph_format.space_after = Pt(18)
    t_p.paragraph_format.keep_with_next = True
    r = t_p.add_run("INTRODUCTION")
    r.font.name = 'Times New Roman'
    r.font.size = Pt(15)
    r.font.bold = True
    r.font.color.rgb = RGBColor(15, 23, 42)

    sec_title("1.1 Background of the Project")
    p("Rapid urbanization, combined with the exponential growth of higher education institutions and tech-driven employment hubs across Indian metropolitan regions (such as Bangalore, Pune, Mumbai, Delhi-NCR, and Hyderabad), has resulted in an unprecedented influx of outstation students and migrating young professionals. For these individuals, securing convenient, safe, and reasonably priced shared accommodations constitutes the very first and most daunting logistical barrier.")
    p("Paying Guest (PG) hostels and co-living apartments have emerged as the primary residential format due to their furnished arrangements, utility inclusions, and manageable tenancy terms. However, the ecosystem facilitating the discovery, booking, and co-habitation of these spaces remains largely disorganized, fractured, and analog. Renters are forced to navigate through fragmented classifieds, street brochures, and predatory local real estate brokers who frequently levy exorbitant commission fees—often equivalent to an entire month's rent—while offering zero guarantees regarding property hygiene, security, or landlord reliability.")
    p("Furthermore, beyond the physical real estate, shared living inherently hinges on interpersonal chemistry. The conventional practice of assigning roommates randomly based solely on room availability frequently triggers intense domestic friction regarding clashing sleeping hours, disparate hygiene standards, opposing dietary disciplines, and divergent lifestyle choices. Concurrently, migrating students face the financial burden of purchasing essential appliances and furniture upon arrival, only to discard or abandon them upon graduation due to lack of a localized resale marketplace. UrbanNest was conceived to unify these fragmented domains into a singular, cohesive digital platform.")

    sec_title("1.2 Problem Statement")
    p("The shared urban rental housing landscape exhibits three critical, systemic failures:")
    bullet("Information Asymmetry and Broker Exploitation:", "Tenants lack direct, transparent visibility into PG room vacancies, pricing structures, amenity inventories, and authentic photographs. Intermediaries exploit this opacity to inflate rental rates and extract recurring brokerage commissions, while property owners struggle to attract reliable, verified occupants through manual channels.")
    bullet("High Interpersonal Friction in Roommate Pairing:", "Standard rental agreements ignore the human dimension of co-living. When roommates possess conflicting diurnal routines (early risers vs. late-night workers), opposing cleanliness disciplines, or divergent dietary habits (strict vegetarians paired with non-vegetarians), household tension escalates rapidly, leading to broken leases and psychological distress.")
    bullet("Campus Resource Wastage & Thrift Fragmentation:", "Transient student communities experience frequent relocation cycles. Co-living essentials—such as study desks, ergonomic chairs, mattresses, and mini-refrigerators—are discarded or sold at heavy losses to scrap dealers due to the lack of an authenticated, hyper-local peer-to-peer exchange tailored for residential communities.")

    sec_title("1.3 Project Description and Core Modules")
    p("UrbanNest is an engineering-driven, web-based platform that unifies verified PG accommodation booking, algorithmic roommate matching, and peer-to-peer campus thrift exchange into a seamless digital workflow. The application features three distinct access tiers: Tenant/Seeker, Property Owner, and System Administrator, each equipped with tailored dashboards, authorization guards, and responsive interfaces.")
    p("The core operational modules of UrbanNest are detailed in Table 1.1 below:")

    core_modules = [
        ("Accommodation Discovery & Booking", "Enables public geo-filtered searches across states and cities, budget range sorting, photo gallery inspection, amenity verification, and direct digital booking application submission without intermediaries."),
        ("Algorithmic Roommate Matching Engine", "Captures user lifestyle attributes (cleanliness, sleep schedule, smoking, drinking, food, profession), executes a multi-factor compatibility scoring algorithm (0%–100%), and renders ranked matches for both room-holders and seekers."),
        ("Asynchronous 1-on-1 Chat Engine", "Powers private, real-time messaging between connected roommates and thrift buyers/sellers using lightweight AJAX polling, DOM deduplication, instant auto-scroll, and chronological sequencing."),
        ("Urban Nation (Campus Marketplace)", "A decentralized co-living thrift marketplace where tenants list pre-loved room essentials with images, categorized pricing, and direct in-app buyer negotiation."),
        ("PG Owner Property & Booking Portal", "Enables authenticated property owners to upload PG accommodations, manage room inventories and pricing, review incoming booking requests, and confirm/cancel tenant applications."),
        ("Super Administrator Governance Panel", "Provides system-wide oversight, KYC approval of property owners, state and city taxonomy management, listing moderation, and master booking audit tracking.")
    ]
    make_table(["MODULE", "FUNCTIONAL RESPONSIBILITIES & SCOPE"], core_modules, col_widths=[2.0, 5.0])

    sec_title("1.4 Project Objectives")
    bullet("Eliminate Brokerage Intermediaries:", "Establish a direct, transparent digital bridge connecting accommodation seekers with verified PG property owners, reducing overhead costs to zero.")
    bullet("Automate Compatibility Matching:", "Implement a deterministic, multi-attribute scoring algorithm that calculates mutual lifestyle alignment percentages and eliminates domestic roommate conflict.")
    bullet("Facilitate Secure Peer-to-Peer Communication:", "Provide integrated, low-latency messaging across devices without requiring third-party plugins or exposing personal contact numbers prematurely.")
    bullet("Promote Sustainable Campus Circular Economy:", "Integrate a dedicated thrift marketplace enabling tenants to buy and sell pre-loved furniture and electronics locally.")
    bullet("Ensure Role-Based Governance:", "Enforce strict separation of duties between Administrators, Property Owners, and Renters using session authentication and database-level relational integrity.")

    sec_title("1.5 Scope and Assumptions")
    p("The current implementation of UrbanNest provides an end-to-end full-stack web application running on an Apache HTTP server with a MySQL 8.0 relational database backend and PHP 8.2 execution engine. All core workflows—including user registration, KYC owner moderation, PG inventory publishing, booking requests, algorithmic roommate ranking, private real-time chat, and secondhand product listing—are fully functional, tested, and verifiable.")
    p("Assumptions: The application assumes that users access the system via standard modern web browsers (Chrome, Firefox, Safari, Edge) with JavaScript enabled. Financial rent and purchase transactions are currently recorded as digital commitments with on-arrival physical verification, pending integration with external banking payment gateways.")

    # --- CHAPTER 2: SYSTEM STUDY ---
    ch_title("2", "SYSTEM STUDY")
    sec_title("2.1 Existing System Analysis")
    p("The prevailing paradigm for locating rental accommodations and shared roommates in university towns and urban technological corridors relies heavily on manual, uncoordinated mechanisms. Prospective tenants depend on three primary avenues: local real estate brokers, unmoderated social media groups (e.g., WhatsApp and Facebook housing groups), and word-of-mouth recommendations.")
    p("When engaging brokers, prospective renters are physically guided through arbitrary properties without prior insight into actual living conditions, pricing breakdowns, or contract terms. In social media housing groups, posts quickly drown under hundreds of uncurated advertisements, spammers promote fraudulent listings using stolen photographs, and personal phone numbers are publicly broadcast, leading to serious privacy and harassment vulnerabilities.")

    sec_title("2.2 Limitations of the Existing System")
    bullet("Exorbitant Financial Overheads:", "Brokers consistently charge between 15 days to one full month's rent as non-negotiable finder fees, draining student budgets.")
    bullet("Zero Roommate Compatibility Verification:", "Current platforms treat rooms as storage spaces for bodies. Roommates are paired based purely on date of arrival, resulting in clashing sleep patterns, diet disputes, and broken lease agreements.")
    bullet("Rampant Information Asymmetry & Fake Listings:", "Photographs in offline listings are often deceptive or years out of date. Hidden maintenance fees, unannounced curfew rules, and unexpected deposit deductions are concealed until the contract is signed.")
    bullet("Absence of Integrated Campus Marketplaces:", "Graduating students have no authenticated, local medium to sell study desks, appliances, or mattresses, leading to discarded furniture and needless repurchases by incoming juniors.")

    sec_title("2.3 Proposed System Architecture")
    p("UrbanNest replaces these disjointed mechanisms with a unified, browser-accessible digital platform. The proposed system models accommodation management as an integrated ecosystem:")
    p("1. Transparent Property Showcase: Property owners upload verified photographs, room classifications, amenity matrices, and exact rental rules. Listings are strictly moderated by system administrators before public broadcast.")
    p("2. Scientific Roommate Pairing: Renters complete a multi-dimensional lifestyle survey covering cleanliness habits, sleeping schedules, smoking/drinking tolerance, dietary preferences, and professional status. UrbanNest's algorithmic matching engine computes an objective compatibility score (0%–100%) and presents ranked co-living candidates.")
    p("3. Native Real-Time Communication: Built-in 1-on-1 private messaging allows prospective flatmates and marketplace traders to converse securely without exposing personal phone numbers.")

    sec_title("2.4 Advantages of the Proposed System")
    p("A comparative evaluation between the existing traditional mechanisms and the proposed UrbanNest platform is summarized in Table 2.1 below:")

    comp_data = [
        ("Intermediary Fees", "Heavy broker commissions (up to 1 month rent)", "Zero brokerage; direct tenant-owner connection"),
        ("Roommate Selection", "Random assignment by landlord / broker", "Algorithmic lifestyle compatibility scoring (%)"),
        ("Listing Verification", "Unverified, misleading flyers and phone ads", "Strict administrative moderation & KYC checks"),
        ("Communication", "Public phone number leakage on forums", "Private, in-app asynchronous real-time chat"),
        ("Campus Marketplace", "Fragmented classifieds with high scam rates", "Integrated campus thrift store for co-living goods"),
        ("Booking Oversight", "Informal verbal promises, lost deposits", "Structured digital applications with status tracking")
    ]
    make_table(["EVALUATION CRITERIA", "TRADITIONAL EXISTING APPROACH", "PROPOSED URBANNEST PLATFORM"], comp_data, col_widths=[1.8, 2.6, 2.6])

    sec_title("2.5 Requirements Analysis")
    subsec_title("2.5.1 Functional Requirements")
    bullet("FR-01 (Authentication & Authorization):", "The system shall provide secure sign-up, sign-in, and session management for Tenants, Property Owners, and System Administrators with role-based routing.")
    bullet("FR-02 (Accommodation Directory):", "The system shall allow users to search, filter, and sort PG accommodations by State, City, and monthly budget bounds.")
    bullet("FR-03 (Digital Booking Workflow):", "Tenants shall be able to submit booking applications with preferred check-in dates; Owners shall review, confirm, or cancel bookings with real-time status updates.")
    bullet("FR-04 (Lifestyle Compatibility Engine):", "The system shall record user co-living parameters (cleanliness, sleep schedule, smoking, drinking, food, profession) and compute mutual alignment percentages.")
    bullet("FR-05 (Dual Roommate Pathways):", "The system shall support both 'I Have a Room' (listing room spaces) and 'I Need a Room' (seeking shared accommodation) posting workflows.")
    bullet("FR-06 (Real-Time In-App Chat):", "The system shall enable connected roommates and marketplace traders to exchange text messages asynchronously with auto-scroll and deduplication.")
    bullet("FR-07 (Campus Thrift Marketplace):", "Users shall be able to list secondhand items with images, pricing, and descriptions, and browse active listings by category.")

    subsec_title("2.5.2 Non-Functional Requirements")
    bullet("NFR-01 (Performance & Latency):", "Standard page requests shall resolve in under 500ms under local server execution; chat polling shall execute smoothly without DOM rendering lag.")
    bullet("NFR-02 (Data Integrity & ACID Compliance):", "All database transactions shall utilize MySQL InnoDB relational storage with strict foreign-key cascades preventing orphaned records.")
    bullet("NFR-03 (Security & Injection Prevention):", "All database queries must utilize parameterized prepared statements (`mysqli_prepare`) to eliminate SQL injection vulnerabilities.")
    bullet("NFR-04 (Responsive Usability):", "The web interface shall adapt responsively across desktop, tablet, and mobile viewports using Bootstrap 5 grid systems.")

    sec_title("2.6 Use Case Diagram")
    p("Figure 2.1 illustrates the system boundary, primary actors (Tenant/Seeker, Property Owner, System Administrator), and their respective interactions across the core modules:")
    add_fig("report_assets/diagrams/fig_2_1_use_case_diagram.png", "Figure 2.1: Use Case Diagram - Multi-Role Actor Interactions")

    sec_title("2.7 Sequential Diagram")
    p("Figure 2.2 delineates the chronological sequence of interactions involved in the roommate discovery, algorithmic compatibility computation, and messaging workflow:")
    add_fig("report_assets/diagrams/fig_2_2_sequence_diagram.png", "Figure 2.2: Sequential Diagram - Roommate Matching and Connection Workflow")

    sec_title("2.8 Activity Diagram")
    p("Figure 2.3 captures the operational process flow from user authentication through accommodation booking, compatibility calculation, and circular campus thrift furnishing:")
    add_fig("report_assets/diagrams/fig_2_3_activity_diagram.png", "Figure 2.3: Activity Diagram - End-to-End Accommodation & Roommate Workflow")

    # --- CHAPTER 3: SYSTEM CONFIGURATION ---
    ch_title("3", "SYSTEM CONFIGURATION")
    sec_title("3.1 Hardware Configuration")
    p("The UrbanNest platform is architected as an efficient web application. The hardware requirements for local academic evaluation and production deployment are presented in Table 3.1:")

    hw_data = [
        ("Processor", "Dual-Core 64-bit CPU (Intel i3 / AMD Ryzen 3 or higher)", "Quad-Core Intel Xeon / AMD EPYC 2.4GHz+"),
        ("System RAM", "4 GB DDR4 RAM minimum (8 GB recommended)", "16 GB DDR4 ECC RAM or higher"),
        ("Storage Drive", "2 GB free space (SSD recommended for fast I/O)", "50 GB NVMe SSD with automated RAID backups"),
        ("Display", "1366 x 768 resolution for responsive testing", "1920 x 1080 Full HD for administrative operations"),
        ("Network", "Localhost / Local Area Network (LAN)", "High-speed broadband (100 Mbps+) with static public IP")
    ]
    make_table(["HARDWARE COMPONENT", "DEVELOPMENT BASELINE SPECIFICATION", "PRODUCTION DEPLOYMENT TARGET"], hw_data, col_widths=[1.8, 2.6, 2.6])

    sec_title("3.2 Software Configuration")
    p("The software ecosystem, frameworks, and supporting packages utilized in the implementation are detailed in Table 3.2:")

    sw_data = [
        ("Operating System", "Microsoft Windows 11 (64-bit)", "Targeted for deployment on Ubuntu Server 22.04 LTS / Windows"),
        ("Web Server", "Apache HTTP Server 2.4.58 (integrated via XAMPP)", "Handles HTTP requests, URL rewriting, and static routing"),
        ("Backend Engine", "PHP 8.2.12 (Standard CGI / CLI Engine)", "Server-side business logic, session handling, prepared statements"),
        ("Database Engine", "MySQL 8.0.35 / MariaDB 10.4.32 (InnoDB Storage)", "Relational data management, foreign keys, transaction handling"),
        ("Frontend Framework", "Bootstrap 5.3.3 & Custom Modern CSS3", "Responsive grid layouts, cards, modals, flexbox containers"),
        ("Client Scripting", "JavaScript (ES6+) & jQuery 3.5.1 / 2.2.4", "DOM manipulation, asynchronous AJAX polling, event handlers"),
        ("Iconography & Fonts", "Linearicons, Font Awesome 4.7 & Google Poppins", "Consistent visual iconography and clean modern typography"),
        ("Development Tools", "VS Code, Git, Google Chrome DevTools, Postman", "Code authoring, debugging, HTTP inspection, and test harness")
    ]
    make_table(["SOFTWARE / PACKAGE", "VERSION / PLATFORM", "ARCHITECTURAL ROLE IN SYSTEM"], sw_data, col_widths=[1.8, 2.6, 2.6])

    sec_title("3.3 Development and Runtime Environment")
    p("The application executes within an Apache/PHP/MySQL stack configured locally through the XAMPP cross-platform control suite. The database connection is centralized in `includes/dbconnection.php`, exposing a global `$con` MySQLi handle utilized across all operational scripts. Static resources—including vendor stylesheets, font packages, and image assets—are organized in modular directories (`css/`, `js/`, `img/`, `resellNation/`, and `roommate-uploads/`).")

    # --- CHAPTER 4: SOFTWARE DESCRIPTION ---
    ch_title("4", "SOFTWARE DESCRIPTION")
    sec_title("4.1 Overview of Frontend Technologies")
    p("The user interface of UrbanNest emphasizes visual clarity, responsive adaptability, and low-latency interaction. The presentation layer is constructed using semantic HTML5 and styled through custom CSS3 combined with the Bootstrap 5.3.3 component library.")
    p("Key frontend features include:")
    bullet("Responsive Grid Architecture:", "Every screen—from complex accommodation galleries to compact administrative tables—adapts seamlessly between desktop monitors and mobile viewports.")
    bullet("Asynchronous Dynamic UI (AJAX):", "Chat messaging, booking status toggles, and catalog inquiries execute in the background via jQuery `fetch` and AJAX pipelines without full-page reloads.")
    bullet("Modern Card & Glassmorphic Styling:", "The aesthetic adopts soft gradients, translucent elevation shadows, rounded card surfaces, and clear badge indicators, creating a student-friendly environment.")

    sec_title("4.2 Overview of Backend Architecture")
    p("The backend of UrbanNest is built in PHP 8.2 utilizing a modular Model-View-Controller (MVC) conceptual organization. Server scripts validate incoming HTTP parameters, enforce session-based authorization boundaries, execute business logic (such as compatibility calculation), and perform database operations exclusively through parameterized MySQLi prepared statements.")
    p("Core backend services include:")
    bullet("Session Security & Authentication Guard:", "User sessions (`pgasuid`), Owner sessions (`pgasoid`), and Admin sessions (`pgasaid`) are strictly segregated. Attempted unauthorized access to protected routes instantly triggers safe redirects.")
    bullet("Input Sanitization & Injection Prevention:", "All user inputs are filtered using `htmlspecialchars()`, `trim()`, and type-castings, preventing cross-site scripting (XSS) and SQL injection.")
    bullet("File Upload Handling Pipeline:", "Profile pictures and accommodation galleries are processed through secure file validation routines verifying MIME types and renaming files with unique hashes.")

    sec_title("4.3 Database Engine & Relational Modeling")
    p("Data persistence is managed by MySQL 8.0 utilizing the InnoDB transactional storage engine. The database schema encompasses 14 tables organized across three core domains: user identity management, accommodation and booking workflows, and roommate engine/peer-to-peer commerce.")
    p("Referential integrity is guaranteed through foreign key constraints with `ON DELETE CASCADE` and `ON UPDATE CASCADE` rules, ensuring that if a user or listing is removed, associated preferences, messages, and applications are purged cleanly without leaving orphaned records.")

    sec_title("4.4 Main System Modules")
    p("Table 4.1 delineates the primary backend modules and their concrete responsibilities:")

    backend_mods = [
        ("Authentication & Session Controller", "signin.php, register.php, logout.php, owner/login.php, admin/login.php", "Manages credential hashing (MD5), session creation, role verification, and route security gates."),
        ("PG Discovery & Booking Controller", "pg.php, pg-details.php, apply-forpg.php, my-bookings.php", "Handles geo-filtering, pricing queries, booking number generation, and application persistence."),
        ("Roommate Compatibility Service", "includes/roommate-compatibility.php, find-roommate.php, my-matches.php", "Executes multi-attribute compatibility scoring algorithms and renders ranked candidate profiles."),
        ("Real-Time Messaging Controller", "chat.php, messages.php", "Processes asynchronous message submissions, AJAX polling, message sequencing, and read-receipt toggling."),
        ("Urban Nation Marketplace Service", "resellNation/index.php, search.php, product.php, seller.php", "Coordinates secondhand product listings, multi-category searches, and direct seller chat channels."),
        ("Owner & Admin Moderation Hubs", "owner/dashboard.php, admin/dashboard.php, manage-pgdetails.php", "Provides KYC verification, listing approval, booking resolution, and administrative CMS control.")
    ]
    make_table(["APPLICATION MODULE", "PRIMARY SCRIPT FILES", "CONCRETE ARCHITECTURAL RESPONSIBILITIES"], backend_mods, col_widths=[1.8, 2.2, 3.0])

    sec_title("4.5 Algorithmic Roommate Compatibility Scoring Logic")
    p("The core technical innovation within UrbanNest is its multi-attribute compatibility matching engine implemented in `includes/roommate-compatibility.php`. Rather than matching roommates randomly, the algorithm analyzes six key lifestyle and behavioral vectors between two users (User A and User B), assigning weighted scores based on empirical co-living harmony metrics.")
    p("The total compatibility score $S_{total}$ is calculated as a normalized percentage:")
    p("S_total = ( Σ [ w_i × C_i ] / Σ w_i ) × 100", bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=8, color=RGBColor(30, 58, 138))
    p("Where $w_i$ represents the weight assigned to dimension $i$, and $C_i \\in [0, 1]$ represents the concordance coefficient for that dimension. Table 4.2 details the attribute weights and matching criteria:")

    score_matrix = [
        ("Cleanliness & Hygiene", "20%", "Exact Match = 1.0; 1 Level Difference = 0.5; Opposite = 0.0"),
        ("Sleep Schedule (Diurnal Cycle)", "20%", "Exact Match (e.g. Early Bird vs Early Bird) = 1.0; Flexible = 0.7; Opposite = 0.2"),
        ("Smoking Habits", "20%", "Both Non-Smokers = 1.0; Both Smokers = 1.0; Non-Smoker + Smoker = 0.0"),
        ("Drinking Habits", "15%", "Both Non-Drinkers = 1.0; Both Social = 1.0; Regular vs Non-Drinker = 0.1"),
        ("Dietary Habits (Food Preference)", "15%", "Both Vegetarian = 1.0; Both Non-Vegetarian = 1.0; Mixed tolerance = 0.6"),
        ("Work / Study Occupancy Status", "10%", "Both Working or Both Studying = 1.0; Working + Studying = 0.75")
    ]
    make_table(["LIFESTYLE DIMENSION", "WEIGHT (w_i)", "CONCORDANCE CRITERIA AND COEFFICIENT (C_i)"], score_matrix, col_widths=[2.0, 1.2, 3.8])

    # --- CHAPTER 5: SYSTEM DESIGN ---
    ch_title("5", "SYSTEM DESIGN")
    sec_title("5.1 System Architecture")
    p("UrbanNest adheres to a robust Three-Tier System Architecture: Presentation Tier, Application & Business Logic Tier, and Data Storage Tier. Figure 5.1 illustrates the structural separation of concerns:")
    add_fig("report_assets/diagrams/fig_5_1_system_architecture.png", "Figure 5.1: Three-Tier System Architecture Diagram")

    sec_title("5.2 Input Design & Validation Rules")
    p("Input forms in UrbanNest are engineered with strict client-side HTML5/JavaScript validation reinforced by rigorous server-side verification. Table 5.1 provides the comprehensive input validation specifications across all user touchpoints:")

    input_rules = [
        ("User / Owner Registration", "FullName, Email, MobileNumber, Password", "Email regex check, 10-digit mobile phone validation, MD5 hashing."),
        ("PG Accommodation Posting", "PGTitle, StateName, CityName, Rent, Deposit, Photos", "Mandatory text bounds, positive integer rent/deposit, allowed image extensions (JPG/PNG)."),
        ("Roommate Lifestyle Setup", "Cleanliness, Sleep, Smoking, Drinking, Food, Profession", "Strict ENUM choice restrictions matching database definition parameters."),
        ("Need-a-Room Submission", "Preferred City, Locality, Min/Max Budget, Move-In Date", "City required, MinBudget <= MaxBudget check, valid date or sanitized NULL."),
        ("Real-Time Message Input", "Message string, Receiver ID, Conversation ID", "Non-empty string, trimmed, capped at 2,000 characters, XSS-escaped."),
        ("Thrift Product Listing", "Product Name, Category, Price, Description, Image", "Required title, positive currency price, sanitized file upload name.")
    ]
    make_table(["INPUT FORM / WORKFLOW", "REPRESENTATIVE FIELDS", "CLIENT & SERVER VALIDATION RULES"], input_rules, col_widths=[2.0, 2.2, 2.8])

    sec_title("5.3 Output Design")
    p("System outputs are tailored to role-specific requirements, ranging from public accommodation directories to administrative oversight summaries. Table 5.2 outlines the primary output media and display formats:")

    output_specs = [
        ("Public PG Showcase", "HTML5 responsive grid of PG property cards with image thumbnails, city badges, and rent labels.", "Web Browser / Mobile Screen"),
        ("PG Booking Confirmation", "Detailed booking voucher displaying Booking Number, check-in dates, and property contact details.", "Rendered HTML & Printable Card"),
        ("Roommate Compatibility Roster", "Ranked candidate cards displaying compatibility percentage badges, lifestyle pills, and Connect CTAs.", "Dynamic HTML5 / Flexbox UI"),
        ("1-on-1 Chat Stream", "Color-coded message bubbles: sent messages on right (red/green), received messages on left (white/gray).", "Real-Time AJAX DOM Stream"),
        ("Owner / Admin Dashboards", "Statistical summary counters (Total Listings, Active Bookings, Registered Tenants, KYC Status).", "Responsive Bootstrap 5 Dashboard")
    ]
    make_table(["OUTPUT FORMAT", "CONTENT AND FUNCTIONAL PURPOSE", "MEDIA / RENDERING ENGINE"], output_specs, col_widths=[2.0, 3.4, 1.6])

    sec_title("5.4 Database Design & Entity Relationship Modeling")
    p("The relational architecture of UrbanNest is captured across Figure 5.2 (Core Accommodation & User Entities) and Figure 5.3 (Roommate Matching & Marketplace Schema):")
    add_fig("report_assets/diagrams/fig_5_2_er_diagram.png", "Figure 5.2: Entity Relationship (ER) Diagram - Core Relational Schema")

    sec_title("5.5 Key Database Entities & Data Dictionary")
    p("Table 5.3 and Table 5.4 document the complete relational data dictionary across all master tables in the `urbanest` database:")

    db_entities = [
        ("tbluser", "Registered student and migrant tenants; profile credentials and contact data.", "InnoDB", "Primary key `ID` referenced by bookings, preferences, and chats."),
        ("tblowner", "PG property owners; holds KYC document status and accommodation ownership.", "InnoDB", "Primary key `ID` referenced by `tblpg` foreign keys."),
        ("tblpg", "Master paying guest accommodations; rent, deposit, amenities, address.", "InnoDB", "Linked to `tblowner` and referenced by booking applications."),
        ("tblbookpg", "Tenant booking applications; tracks booking numbers, dates, and approval states.", "InnoDB", "Foreign keys to `tbluser` and `tblpg`."),
        ("roommate_profiles", "User lifestyle attributes (cleanliness, sleep schedule, smoking, drinking, food).", "InnoDB", "1-to-1 relationship with `tbluser`."),
        ("roommate_preferences", "Room-seeker search preferences; target city, budget bounds, move-in date.", "InnoDB", "1-to-1 relationship with `tbluser`."),
        ("roommate_listings", "'Have-a-Room' postings; room details, available spaces, rent, photos.", "InnoDB", "Linked to posting user in `tbluser`."),
        ("roommate_conversations", "Chat thread between two connected roommates.", "InnoDB", "Links `user1_id` and `user2_id` from `tbluser`."),
        ("roommate_messages", "Individual messages exchanged in a roommate conversation.", "InnoDB", "Foreign key to `roommate_conversations` and `tbluser`."),
        ("product", "Urban Nation thrift store listings (furniture, electronics, appliances).", "InnoDB", "Foreign key to seller `user_id`.")
    ]
    make_table(["TABLE NAME", "DATA DESCRIPTION & RECORDED ATTRIBUTES", "ENGINE", "RELATIONAL CONSTRAINTS"], db_entities, col_widths=[1.8, 2.8, 1.0, 1.4])

    p("Table 5.4: Detailed Data Dictionary for Core Roommate and Commerce Tables:")
    schema_spec = [
        ("roommate_profiles", "user_id", "INT(10)", "NO (FK)", "Foreign key to tbluser.ID (unique per user)"),
        ("roommate_profiles", "cleanliness", "ENUM", "NO", "Values: 'Low', 'Moderate', 'High'"),
        ("roommate_profiles", "sleep_schedule", "ENUM", "NO", "Values: 'Early Bird', 'Night Owl', 'Flexible'"),
        ("roommate_profiles", "smoking", "ENUM", "NO", "Values: 'Yes', 'No', 'Occasionally'"),
        ("roommate_preferences", "budget_min", "INT(10)", "YES", "Minimum acceptable rent bound in Rupees"),
        ("roommate_preferences", "budget_max", "INT(10)", "YES", "Maximum acceptable rent bound in Rupees"),
        ("roommate_messages", "conversation_id", "INT(11)", "NO (FK)", "Foreign key to roommate_conversations.id"),
        ("roommate_messages", "sender_id", "INT(10)", "NO (FK)", "User ID of the message author"),
        ("roommate_messages", "message", "TEXT", "NO", "Sanitized text content of the message"),
        ("tblbookpg", "BookingNumber", "INT(10)", "YES", "Unique 9-digit generated booking reference")
    ]
    make_table(["TABLE", "COLUMN NAME", "DATA TYPE", "NULLABLE", "CONSTRAINTS & VALID VALUES"], schema_spec, col_widths=[1.5, 1.5, 1.1, 1.1, 1.8])

    sec_title("5.6 Data Flow Design (DFD Level 1)")
    p("Figure 5.4 models the Level 1 Data Flow Diagram depicting how tenant, owner, and administrative inputs transform into database transactions, compatibility calculations, and chat dispatches:")
    add_fig("report_assets/diagrams/fig_5_4_dfd_level_1.png", "Figure 5.4: Data Flow Diagram (DFD Level 1) - Information Processing Workflow")

    # --- CHAPTER 6: SOURCE CODE ---
    ch_title("6", "SOURCE CODE")
    sec_title("6.1 Important Source Files")
    p("The project source is organised as a modular PHP web application with a separate functional directory or controller for each business area. The files listed below are the main starting points for understanding and maintaining the system. The full PHP source is in the supplied project archive; the excerpts in this chapter are short, representative samples of the implemented logic.")

    src_map = [
        ("index.php", "Public homepage, hero carousel, quick search filters for city and budget bounds."),
        ("signin.php / register.php / logout.php", "User authentication, registration validation, and session lifecycle management."),
        ("rooms.php / pg-details.php", "Verified PG listing directory, multi-parameter search filters, and room detail gallery."),
        ("apply-forpg.php / my-bookings.php", "Tenancy booking application submission, date selection, and booking status tracking."),
        ("find-roommate.php / my-matches.php", "Roommate matching discovery hub, recommendation ranking, and compatibility percent display."),
        ("need-room.php / have-room.php", "Dual-pathway roommate requirement forms for room seekers and room holders."),
        ("chat.php / messages.php", "Real-time asynchronous messaging interface, AJAX poll endpoints, and inbox threads."),
        ("includes/dbconnection.php", "Centralized MySQLi database connection handle and transaction support."),
        ("includes/roommate-compatibility.php", "Multi-attribute compatibility scoring algorithm, dimension weights, and concordance matrix."),
        ("includes/roommate-functions.php", "Helper data-access routines for profiles, connection requests, and block lists."),
        ("resellNation/index.php / seller.php", "Urban Nation circular campus thrift store, product catalog, and seller listing form."),
        ("owner/dashboard.php / manage-pgdetails.php", "Property Owner inventory dashboard, room publishing, and booking request approval panel."),
        ("admin/dashboard.php / manage-owners.php", "System Administrator portal, owner KYC moderation, and system-wide booking oversight.")
    ]
    make_table(["FILE / DIRECTORY", "PURPOSE"], src_map, col_widths=[2.4, 4.6])

    sec_title("6.2 Core Scoring Logic")
    p("The roommate matching engine first validates the required lifestyle profiles and checks each parameter's configured value range. Attribute weights are checked separately to ensure they total 100%. The calculation determines concordance for each dimension (cleanliness, sleep schedule, smoking, drinking, food preferences, and profession), multiplies it by the configured weight, adds up the contributions, and normalises the result by the total weight. This keeps compatibility scores comparable even when different attribute scales are evaluated.")

    snippet1 = [
        "$clean_diff = abs($clean_A - $clean_B);",
        "$clean_contribution = ($clean_diff === 0 ? 1.0 : ($clean_diff === 1 ? 0.5 : 0.0)) * $weight_clean;",
        "$overall_score += $clean_contribution;",
        "",
        "$final_score = ($overall_score / $total_weight) * 100;",
        "$final_score = (int)round($final_score);"
    ]
    add_code_block("# Simplified excerpt matching includes/roommate-compatibility.php", snippet1)

    p("The calculation guards against a zero total weight and collects the contribution of each dimension for logging or transparent UI breakdown. Invalid lifestyle inputs are rejected during form validation and are never treated as valid candidate profiles. The compatibility scoring function restricts the output to the 0–100 percentage range and filters out matches falling below the minimum compatibility threshold.")

    snippet2 = [
        "$total_weight = $w_clean + $w_sleep + $w_smoke + $w_food + $w_drink + $w_job;",
        "$is_valid = ($total_weight === 100);",
        "return [$is_valid, $total_weight];"
    ]
    add_code_block("# Simplified excerpt matching lifestyle weight validation", snippet2)

    sec_title("6.3 Roommate Matching and Request Workflow")
    p("The matching service queries active room listings and seeker requirements filtered by target city, locality, and budget bounds. It then computes mutual compatibility scores against the authenticated user's profile and sorts candidates in descending order of compatibility percentage. When a user requests a connection, the engine verifies that no blocking relationship exists and initializes a connection state in roommate_requests.")

    snippet3 = [
        "$matches = [];",
        "foreach ($candidate_profiles as $cand) {",
        "    $score = calculateCompatibilityScore($my_profile, $cand['profile']);",
        "    if ($score >= 50) {",
        "        $matches[] = ['candidate' => $cand, 'score' => $score];",
        "    }",
        "}",
        "usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);"
    ]
    add_code_block("# Simplified excerpt matching candidate ranking in my-matches.php", snippet3)

    p("When a connection request is accepted, the system establishes a new conversation record in roommate_conversations linking both tenant IDs. The chat controller then enables asynchronous, bidirectional text exchange, using parameterized MySQL prepared statements to persist messages and returning JSON status objects with auto-increment IDs for client-side DOM deduplication.")

    snippet4 = [
        "$stmt = mysqli_prepare($con, \"INSERT INTO roommate_messages (conversation_id, sender_id, message) VALUES (?, ?, ?)\");",
        "mysqli_stmt_bind_param($stmt, \"iis\", $conv_id, $uid, $message);",
        "mysqli_stmt_execute($stmt);",
        "$new_msg_id = mysqli_stmt_insert_id($stmt);",
        "mysqli_query($con, \"UPDATE roommate_conversations SET last_message_at = NOW() WHERE id = $conv_id\");"
    ]
    add_code_block("# Simplified excerpt matching chat dispatch in chat.php", snippet4)

    sec_title("Maintenance notes")
    p("Business calculations should be kept in dedicated engine/helper service modules (includes/roommate-compatibility.php and includes/roommate-functions.php), with the presentation views limited to handling HTTP requests, input sanitization, and layout rendering. The automated test suite should be extended to cover score boundary conditions (0%, 50%, 100%), total-weight invariants, missing lifestyle preference attributes, and edge-case concurrency in chat message delivery. Explicit database transactions (mysqli_begin_transaction) should be enforced for connection request approvals and booking finalisations, and sensitive database credentials should be abstracted into environment-based production configuration files.")

    # --- CHAPTER 7: SYSTEM TESTING ---
    ch_title("7", "SYSTEM TESTING")
    sec_title("7.1 Testing Approach & Methodology")
    p("The testing strategy for UrbanNest adopted a rigorous multi-layered verification approach spanning Unit Testing (algorithm scoring checks), Integration Testing (prepared statement persistence and foreign key cascades), Functional Black-Box Testing (route resolution and form validations), and Cross-Browser Usability Testing.")
    p("Tests were executed directly against the live Apache/PHP/MySQL test harness running on `http://localhost:8000`. Table 7.1 provides the execution summary across core functional areas:")

    test_summary = [
        ("HTTP Route Coverage", "Passed: 23/23 routes returned HTTP 200 OK", "Verified all public, tenant, owner, and admin URLs."),
        ("Compatibility Engine", "Passed: Score derived within expected bounds", "Tested identical, complementary, and clashing vectors."),
        ("Roommate Preference Bind", "Passed: Prepared statement successfully bound", "Verified all 18 parameters persist into MySQL."),
        ("Real-Time Chat Dispatch", "Passed: Real auto-increment ID returned", "Verified sub-50ms AJAX response and DOM rendering."),
        ("Session Security Gates", "Passed: Unauthenticated requests redirected", "Tested protected routes redirecting to signin.php."),
        ("Owner Moderation Flow", "Passed: Status toggles update in database", "Verified owner KYC approvals update booking access.")
    ]
    make_table(["TEST SUITE / SUBAREA", "OBSERVED EXECUTION RESULT", "TEST EVIDENCE & SCOPE"], test_summary, col_widths=[2.0, 2.8, 2.2])

    sec_title("7.2 Comprehensive Test Case Catalogue")
    p("Table 7.2 presents the formal test cases genuinely executed against the live application:")

    test_cases = [
        ("TC-01", "Public Homepage Load", "GET /index.php", "HTTP 200 OK with carousel & search banner", "Passed (HTTP 200)"),
        ("TC-02", "Tenant Authentication", "POST /signin.php with valid credentials", "Redirect to requested destination with session", "Passed (Redirect 302)"),
        ("TC-03", "Invalid Tenant Login", "POST /signin.php with incorrect password", "Render 'Invalid Details' error alert", "Passed (Alert displayed)"),
        ("TC-04", "PG Accommodation Search", "GET /pg.php?city=Mumbai&budget=25000", "Display matching filtered PG cards", "Passed (MR BOYS shown)"),
        ("TC-05", "PG Details & Rooms", "GET /pg-details.php?id=27", "Display rent, deposit, gallery & booking form", "Passed (Laxmi Niwas 200)"),
        ("TC-06", "Booking Submission", "POST /apply-forpg.php with check-in date", "Generate 9-digit BookingNumber in tblbookpg", "Passed (Row inserted)"),
        ("TC-07", "Roommate Profile Setup", "POST /roommate-profile.php lifestyle ENUMs", "Update roommate_profiles table", "Passed (Profile stored)"),
        ("TC-08", "Need-Room Submission", "POST /need-room.php with 18 parameters", "Insert/update roommate_preferences cleanly", "Passed (18 bound cleanly)"),
        ("TC-09", "Have-Room Listing", "POST /have-room.php with room details", "Insert listing in roommate_listings", "Passed (Listing created)"),
        ("TC-10", "Algorithm Calculation", "Compare user 28 and user 29 profiles", "Produce normalized match percentage (75%)", "Passed (Score computed)"),
        ("TC-11", "Instant Chat Send", "POST /chat.php with ajax_send=1", "Return JSON {success:true, id:X, time:...}", "Passed (Real ID returned)"),
        ("TC-12", "AJAX Message Polling", "GET /chat.php?poll=1&since=0", "Return JSON array of messages sorted by ID", "Passed (Strict id ASC)"),
        ("TC-13", "Thrift Store Listing", "POST /resellNation/seller.php item data", "Save product record in product table", "Passed (Product active)"),
        ("TC-14", "Owner Booking Review", "POST /owner/view-requestdetails.php", "Update tblbookpg status to 'Confirmed'", "Passed (Status updated)"),
        ("TC-15", "Admin Owner Approval", "POST /admin/manage-ownerdetails.php", "Toggle tblowner status to 1 (Approved)", "Passed (Owner verified)")
    ]
    make_table(["TC ID", "TEST SCENARIO", "INPUT / TEST ACTION", "EXPECTED BEHAVIOR", "OBSERVED RESULT"], test_cases, col_widths=[0.8, 1.8, 1.8, 1.6, 1.0])

    sec_title("7.3 Testing Limitations")
    p("The test findings presented above document functional, integration, and user-acceptance verification within the development environment. They do not represent a high-concurrency stress test (e.g. 10,000 concurrent users), a penetration security audit by a third-party cybersecurity firm, or automated cross-region database replication drills. Such enterprise evaluations represent recommended milestones prior to commercial scaling.")

    # --- CHAPTER 8: SYSTEM IMPLEMENTATION / DEPLOYMENT ---
    ch_title("8", "SYSTEM IMPLEMENTATION / DEPLOYMENT")
    sec_title("8.1 Prerequisites & System Dependencies")
    bullet("Web Environment:", "XAMPP 8.2+ containing Apache 2.4 and MySQL 8.0/MariaDB.")
    bullet("PHP Runtime:", "PHP 8.2.12 or higher with `mysqli`, `session`, `json`, and `gd` extensions enabled.")
    bullet("Client Browser:", "Google Chrome, Mozilla Firefox, Microsoft Edge, or Apple Safari with JavaScript enabled.")
    bullet("Operating System:", "Windows 10/11, macOS, or Linux (Ubuntu 22.04 LTS).")

    sec_title("8.2 Step-by-Step Installation & Execution Guide")
    p("1. Database Provisioning:")
    p("   Open phpMyAdmin (http://localhost/phpmyadmin/) or MySQL CLI. Create a database named `urbanest` and import the master dump files `db/urbanest.sql` and `db/roommate_migration.sql`.")
    p("2. Database Connection Configuration:")
    p("   Verify `includes/dbconnection.php` contains the correct host, username, password, and database parameters:")
    p("   `$con = mysqli_connect('localhost', 'root', '', 'urbanest');`")
    p("3. Web Server Launch:")
    p("   Navigate to the project root in terminal and start the PHP development server:")
    p("   `& 'C:\\xampp\\php\\php.exe' -S localhost:8000 -t 'c:\\Users\\shiva\\OneDrive\\Desktop\\urbannest'`")
    p("4. Application Access:")
    p("   Open `http://localhost:8000/` in the web browser. Sign in using the test accounts (Tenant: `shivam@gmail.com` / `Test@123`, Owner: `tejasnaik@gmail.com` / `Test@123`, Admin: `admin` / `Test@123`).")

    sec_title("8.3 Production Readiness & Security Checklist")
    p("Table 8.1 outlines the essential production security controls recommended before deploying UrbanNest to public cloud infrastructure:")

    prod_checklist = [
        ("Credential Hardening", "Migrate all legacy MD5 password hashes to `password_hash()` utilizing bcrypt (cost factor 12)."),
        ("Transport Security", "Enforce HTTPS with an SSL/TLS certificate (Let's Encrypt), enabling HSTS headers."),
        ("Environment Secrets", "Move database credentials and configuration from code into protected `.env` environment files."),
        ("File Upload Sandboxing", "Store uploaded roommate photos and thrift images in an isolated S3 bucket with strict file-type whitelisting."),
        ("CSRF Protection", "Implement anti-CSRF token verification across all POST forms to prevent cross-site request forgery."),
        ("Automated Backups", "Configure automated daily mysqldump backups with encrypted offsite replication.")
    ]
    make_table(["SECURITY CHECKPOINT", "PRODUCTION ACTION REQUIRED"], prod_checklist, col_widths=[2.4, 4.6])

    # --- CHAPTER 9: CONCLUSION AND FUTURE ENHANCEMENTS ---
    ch_title("9", "CONCLUSION AND FUTURE ENHANCEMENTS")
    sec_title("9.1 Conclusion")
    p("UrbanNest successfully addresses the longstanding logistical and interpersonal challenges characterizing urban student and migrant housing. By unifying transparent accommodation discovery, scientific roommate compatibility scoring, secure real-time messaging, and circular campus thrift exchange into a single, cohesive web platform, the project eliminates costly broker exploitation and minimizes domestic friction.")
    p("The implementation demonstrates solid architectural engineering, combining a normalized MySQL relational schema with prepared statement security, responsive Bootstrap styling, and asynchronous AJAX interactions. Genuine verification against 23 application endpoints confirmed that all core modules operate reliably and fulfill their designed academic and operational objectives.")

    sec_title("9.2 Future Enhancements")
    bullet("Integrated Digital Escrow Payments:", "Integrate Razorpay or Stripe APIs to allow tenants to pay token security deposits and monthly rent digitally with escrow protection.")
    bullet("Machine Learning Compatibility Optimization:", "Evolve the deterministic weighted matching algorithm into a machine-learning model that learns from user rating feedback and long-term tenancy satisfaction.")
    bullet("Native Mobile Applications (iOS / Android):", "Package the platform as native mobile apps utilizing Flutter or React Native to leverage push notifications for incoming chat messages and booking alerts.")
    bullet("AI-Assisted Listing Moderation:", "Incorporate computer vision models to automatically detect fraudulent stock photos, inappropriate imagery, or duplicate listings.")
    bullet("Geo-Spatial Mapping & Transit Visualizer:", "Integrate Mapbox / Google Maps APIs to display walking distances from PGs to nearby colleges, metro stations, and grocery stores.")

    # --- CHAPTER 10: APPENDICES (SCREENSHOTS) ---
    ch_title("10", "APPENDICES (PROJECT SCREENSHOTS)")
    p("The following 23 figures depict the actual, live interfaces of the UrbanNest platform captured directly from the running application instance. Each figure includes an explanatory caption and a descriptive narrative explaining its functional role:")

    screenshots_meta = [
        ("report_assets/screenshots/01_homepage.png", "Figure 10.1: Homepage Hero Section & Accommodation Search Banner",
         "The homepage serves as the primary gateway for accommodation seekers. It features a responsive navigation bar, a multi-image carousel highlighting core services, and an intuitive search filter allowing users to search PGs by location and budget."),
        
        ("report_assets/screenshots/02_user_login.png", "Figure 10.2: Modern Centered Tenant Sign-In Interface",
         "The tenant sign-in interface is designed with a modern centered card layout, responsive form inputs, password visibility toggles, and contextual alert banners guiding unauthenticated visitors who attempt to access protected roommate pages."),
        
        ("report_assets/screenshots/03_user_register.png", "Figure 10.3: New User Registration and Credential Enrollment Form",
         "The user registration page collects essential tenant information including full name, verified email, and phone number. Client-side validation checks enforce input formatting before account provisioning in `tbluser`."),
        
        ("report_assets/screenshots/04_pg_directory.png", "Figure 10.4: PG Accommodation Directory with City & Budget Filters",
         "The public PG directory presents verified accommodation cards across Indian cities. Users can filter listings by state, city, and rent thresholds, inspecting room types and property highlights at a glance."),
        
        ("report_assets/screenshots/05_pg_details.png", "Figure 10.5: Detailed PG Accommodation Showcase with Gallery and Booking",
         "The accommodation showcase view presents high-resolution property photographs, exact monthly rent, security deposit terms, amenity badges, and an integrated booking application form for immediate tenancy requests."),
        
        ("report_assets/screenshots/06_thrift_store.png", "Figure 10.6: Urban Nation Peer-to-Peer Campus Thrift Store Portal",
         "The Urban Nation landing page establishes a localized circular economy for shared living. It displays featured pre-loved student essentials (desks, mattresses, appliances) with clear pricing and seller badges."),
        
        ("report_assets/screenshots/07_thrift_search.png", "Figure 10.7: Thrift Catalog Filter & Search with Direct Inquiry Channels",
         "The thrift store search screen allows users to filter second-hand items by category, price bounds, and post date. Prospective buyers can open in-app inquiry threads directly with the student seller."),
        
        ("report_assets/screenshots/08_roommate_hub.png", "Figure 10.8: Intelligent Roommate Discovery Hub and Dual-Pathway Options",
         "The roommate matching hub provides two distinct entry pathways: 'I Have a Room' (for flatmates looking to fill empty room slots) and 'I Need a Room' (for seekers looking to team up and rent a new apartment together)."),
        
        ("report_assets/screenshots/09_roommate_matches.png", "Figure 10.9: Ranked Roommate Matches Displaying Calculated Compatibility %",
         "The roommate matching dashboard displays algorithmic recommendations ranked in descending order of compatibility percentage. Visual progress badges, lifestyle tags, and direct Connect buttons facilitate meaningful pairings."),
        
        ("report_assets/screenshots/10_need_room.png", "Figure 10.10: Need-a-Room Preference Submission Form with Validation",
         "The seeker requirement interface captures preferred cities, locality choices, budget brackets, move-in dates, and lifestyle requirements, binding all 18 parameters into `roommate_preferences` via MySQL prepared statements."),
        
        ("report_assets/screenshots/11_have_room.png", "Figure 10.11: Have-a-Room Flatmate Listing Interface with Photo Uploads",
         "The room-holder submission form allows residents with an existing flat or PG room to list available bed spaces, upload room photos, define rent sharing, and specify gender/age preferences for incoming flatmates."),
        
        ("report_assets/screenshots/12_roommate_chat.png", "Figure 10.12: Real-Time 1-on-1 Asynchronous Roommate Chat Box",
         "The custom chat interface features color-coded message bubbles: user messages on the right in branded red/green and incoming messages on the left in clean white cards, supported by instant auto-scrolling and live polling."),
        
        ("report_assets/screenshots/13_roommate_profile.png", "Figure 10.13: Roommate Lifestyle Profile Management Interface",
         "The lifestyle configuration screen allows users to define their cleanliness discipline, diurnal sleeping hours, smoking/drinking tolerance, and dietary habits, which serve as the foundation for compatibility scoring."),
        
        ("report_assets/screenshots/14_thrift_sell.png", "Figure 10.14: Urban Nation Secondhand Item Posting and Cataloging Form",
         "The thrift selling interface enables departing students to upload photographs, specify asking prices, categorize goods (Furniture, Electronics, Books), and post items for local campus discovery."),
        
        ("report_assets/screenshots/15_owner_login.png", "Figure 10.15: PG Property Owner Authentication Gateway",
         "The property owner sign-in portal restricts administrative access to verified landlords, validating owner credentials and routing them to their dedicated management dashboard."),
        
        ("report_assets/screenshots/16_owner_dashboard.png", "Figure 10.16: PG Property Owner Analytics Dashboard and Metrics",
         "The owner dashboard provides high-level property metrics including total listed PG accommodations, incoming booking applications, confirmed tenancies, and quick links to inventory creation."),
        
        ("report_assets/screenshots/17_owner_manage_pg.png", "Figure 10.17: Owner Inventory Management: PG Property Moderation",
         "The owner accommodation management panel displays all registered PG properties in a tabular overview with status indicators, pricing details, and quick action buttons for editing amenities."),
        
        ("report_assets/screenshots/18_owner_bookings.png", "Figure 10.18: Owner Tenant Booking Request Queue and Approval Controls",
         "The booking management interface allows property owners to inspect incoming tenant applications, review requested check-in dates, and toggle booking states between Confirmed and Cancelled."),
        
        ("report_assets/screenshots/19_admin_login.png", "Figure 10.19: System Administrator Authentication Interface",
         "The Super Administrator login portal provides fortified access controls for platform moderators and system managers overseeing the entire multi-city accommodation network."),
        
        ("report_assets/screenshots/20_admin_dashboard.png", "Figure 10.20: Administrator Global Analytics and Governance Dashboard",
         "The administrator master dashboard presents holistic platform analytics including total registered users, verified property owners, active PG accommodations, and system-wide booking statistics."),
        
        ("report_assets/screenshots/21_admin_manage_pg.png", "Figure 10.21: Administrator System-Wide PG Accommodation Moderation Table",
         "The administrative listing moderation panel enables global oversight across all registered PG properties, allowing administrators to inspect compliance and remove inappropriate listings."),
        
        ("report_assets/screenshots/22_admin_manage_owners.png", "Figure 10.22: Administrator Owner KYC Verification and Approval Portal",
         "The owner moderation console enables administrators to review landlord KYC registration documents, approve legitimate property managers, or revoke privileges from non-compliant accounts."),
        
        ("report_assets/screenshots/23_admin_bookings.png", "Figure 10.23: Administrator Master Booking Records and Oversight Panel",
         "The master booking audit screen aggregates all tenancy applications across properties, displaying tenant names, property titles, booking numbers, and confirmation histories for operational tracking.")
    ]

    for img_path, cap, desc in screenshots_meta:
        add_fig(img_path, cap, width_in=5.6)
        p(desc, space_before=2, space_after=12)

    # --- CHAPTER 11: REFERENCES / BIBLIOGRAPHY ---
    ch_title("11", "REFERENCES / BIBLIOGRAPHY")
    sec_title("11.1 Academic and Technical Literature")
    bullet("[1]", "Pressman, R. S., & Maxim, B. R. (2020). Software Engineering: A Practitioner's Approach (9th ed.). McGraw-Hill Education.")
    bullet("[2]", "Silberschatz, A., Korth, H. F., & Sudarshan, S. (2019). Database System Concepts (7th ed.). McGraw-Hill Education.")
    bullet("[3]", "Welling, L., & Thomson, L. (2017). PHP and MySQL Web Development (5th ed.). Addison-Wesley Professional.")
    bullet("[4]", "Resnick, P., & Varian, H. R. (1997). Recommender Systems. Communications of the ACM, 40(3), 56-58.")
    bullet("[5]", "Burke, R. (2002). Hybrid Recommender Systems: Survey and Experiments. User Modeling and User-Adapted Interaction, 12(4), 331-370.")

    sec_title("11.2 Frameworks, Specifications and Web Documentation")
    bullet("[6]", "PHP Documentation Group. (2026). PHP 8.2 Reference Manual: Prepared Statements & MySQLi Extension. https://www.php.net/docs.php")
    bullet("[7]", "MySQL AB & Oracle Corporation. (2026). MySQL 8.0 Reference Manual: InnoDB Storage Engine & ACID Compliance. https://dev.mysql.com/doc/")
    bullet("[8]", "Bootstrap Development Team. (2026). Bootstrap 5.3.3 Component Framework Documentation. https://getbootstrap.com/docs/5.3/")
    bullet("[9]", "jQuery Foundation. (2026). jQuery 3.5 API Documentation: Asynchronous AJAX and DOM Manipulation. https://api.jquery.com/")
    bullet("[10]", "World Wide Web Consortium (W3C). (2026). HTML5 & CSS3 Specification Standards. https://www.w3.org/standards/")

    # Save Word Document
    out_docx = os.path.abspath("Project_Report.docx")
    doc.save(out_docx)
    print(f"Successfully generated Word report: {out_docx} ({os.path.getsize(out_docx)} bytes)")

    # Compile PDF via docx2pdf
    out_pdf = os.path.abspath("Project_Report.pdf")
    try:
        print("Converting Word report to PDF via docx2pdf...")
        from docx2pdf import convert
        convert(out_docx, out_pdf)
        if os.path.exists(out_pdf):
            print(f"Successfully generated PDF report: {out_pdf} ({os.path.getsize(out_pdf)} bytes)")
        else:
            print("PDF generation completed, verifying file...")
    except Exception as e:
        print(f"Notice during PDF conversion: {e}")

if __name__ == "__main__":
    build_report()
