#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Generator XML draw.io untuk Pengujian White Box
Sistem Keuangan TPQ - Data Iuran, Petugas, Kelas
(Tambah / Edit / Hapus terpisah per halaman)
Desain mengikuti contoh: Flowchart (kiri) + Tabel Flowgraph, Rumus, Jalur (kanan)
"""
import xml.etree.ElementTree as ET
import html

# ---------------- Konstanta layout ----------------
COL_X = {'L': 20, 'C': 250, 'R': 490}
SH_W  = 190
ROW_H = 100
Y0    = 100
RIGHT_LANE = 720
TBL_X = 780
TBL_Y = 100
COLW  = [180, 210, 300]   # Flowgraph | Rumus | Jalur
HDR_H = 32
FG_D  = 28                # diameter lingkaran flowgraph
FG_SP = 46                # spasi vertikal flowgraph

STYLE = {
 'start':   "ellipse;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;fontSize=11;",
 'end':     "ellipse;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;fontSize=11;",
 'proc':    "rounded=0;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;fontSize=10;",
 'dec':     "rhombus;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;fontSize=9;",
}
SH_H = {'start': 40, 'end': 40, 'proc': 55, 'dec': 80}

EDGE_STYLE = ("edgeStyle=orthogonalEdgeStyle;rounded=0;html=1;endArrow=block;endFill=1;"
              "strokeColor=#000000;fontSize=10;jettySize=auto;")
FG_NODE_STYLE = ("ellipse;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;"
                 "fontSize=11;fontStyle=1;")
FG_EDGE_STYLE = "curved=1;html=1;endArrow=block;endFill=1;strokeColor=#000000;"

def cell(parent, cid, value="", style="", x=0, y=0, w=0, h=0, vertex=True):
    c = ET.SubElement(parent, 'mxCell', id=cid, style=style, vertex="1", parent="1")
    if value:
        c.set('value', value)
    ET.SubElement(c, 'mxGeometry', x=str(x), y=str(y), width=str(w), height=str(h)).set('as', 'geometry')
    return c

def edge_cell(parent, cid, src, dst, label="", style=EDGE_STYLE, points=None,
              exit_pt=None, entry_pt=None):
    st = style
    if exit_pt:
        st += f"exitX={exit_pt[0]};exitY={exit_pt[1]};exitDx=0;exitDy=0;"
    if entry_pt:
        st += f"entryX={entry_pt[0]};entryY={entry_pt[1]};entryDx=0;entryDy=0;"
    c = ET.SubElement(parent, 'mxCell', id=cid, style=st, edge="1",
                      parent="1", source=src, target=dst)
    if label:
        c.set('value', label)
    g = ET.SubElement(c, 'mxGeometry')
    g.set('relative', '1'); g.set('as', 'geometry')
    if points:
        arr = ET.SubElement(g, 'Array'); arr.set('as', 'points')
        for (px, py) in points:
            ET.SubElement(arr, 'mxPoint', x=str(int(px)), y=str(int(py)))
    return c

def text_cell(parent, cid, value, x, y, w, h, bold=False, italic=False, size=11, align="left"):
    st = (f"text;html=1;align={align};verticalAlign=top;spacing=6;fontSize={size};"
          f"{'fontStyle=1;' if bold else ''}{'fontStyle=2;' if italic else ''}")
    return cell(parent, cid, value, st, x, y, w, h)

def node_center(pos):
    x, y, w, h = pos
    return (x + w / 2, y + h / 2)

def build_page(mxfile, pid, spec):
    diagram = ET.SubElement(mxfile, 'diagram', id=f"pg-{pid}", name=spec['page'])
    model = ET.SubElement(diagram, 'mxGraphModel', dx="1400", dy="900", grid="1",
                          gridSize="10", guides="1", tooltips="1", connect="1",
                          arrows="1", fold="1", page="1", pageScale="1",
                          pageWidth="1600", pageHeight="1400", math="0", shadow="0")
    root = ET.SubElement(model, 'root')
    ET.SubElement(root, 'mxCell', id="0")
    ET.SubElement(root, 'mxCell', id="1", parent="0")

    P = f"{pid}"
    # ---------- Judul ----------
    text_cell(root, f"{P}-title", f"<b>{spec['section']}  Pengujian <i>White Box</i> Halaman {spec['judul']} (Admin)</b>",
              20, 20, 700, 30, size=13)

    # ---------- Flowchart ----------
    nodes = spec['nodes']       # {num: (label, shape, col, row)}
    pos = {}
    maxrow = 0
    for num, (label, shape, colc, row) in nodes.items():
        h = SH_H[shape]
        w = 120 if shape in ('start', 'end') else SH_W
        x = COL_X[colc] + (SH_W - w) / 2
        y = Y0 + row * ROW_H + (80 - h) / 2
        pos[num] = (x, y, w, h)
        maxrow = max(maxrow, row)
        cell(root, f"{P}-n{num}", label, STYLE[shape], x, y, w, h)

    colof = {num: nodes[num][2] for num in nodes}
    rowof = {num: nodes[num][3] for num in nodes}

    for i, e in enumerate(spec['edges']):
        s, d = e[0], e[1]
        lbl = e[2] if len(e) > 2 else ""
        flags = e[3] if len(e) > 3 else ""
        sx, sy, sw, sh = pos[s]; dx, dy, dw, dh = pos[d]
        exit_pt = entry_pt = None; points = None
        cs, cd = colof[s], colof[d]; rs, rd = rowof[s], rowof[d]
        if flags == 'bypass_right':
            exit_pt = (1, 0.5); entry_pt = (1, 0.5)
            points = [(RIGHT_LANE, sy + sh / 2), (RIGHT_LANE, dy + dh / 2)]
        elif cs == cd and rd == rs + 1:
            exit_pt = (0.5, 1); entry_pt = (0.5, 0)
        elif cs == cd and rd > rs:
            exit_pt = (0.5, 1); entry_pt = (0.5, 0)
        elif rd == rs and cd != cs:  # horizontal ke samping
            if COL_X[cd] < COL_X[cs]:
                exit_pt = (0, 0.5); entry_pt = (1, 0.5)
            else:
                exit_pt = (1, 0.5); entry_pt = (0, 0.5)
        elif rd < rs:  # loop balik ke atas
            if cs == 'L':
                exit_pt = (0.5, 0); entry_pt = (0, 0.5)
            elif cs == 'R':
                exit_pt = (0.5, 0); entry_pt = (1, 0.5)
            else:
                exit_pt = (0, 0.5); entry_pt = (0, 0.5)
        else:  # rd > rs, beda kolom
            if cs == 'C' and cd == 'R':
                exit_pt = (1, 0.5); entry_pt = (0.5, 0)
            elif cs == 'C' and cd == 'L':
                exit_pt = (0, 0.5); entry_pt = (0.5, 0)
            elif cs == 'R' and cd == 'C':
                exit_pt = (0.5, 1); entry_pt = (1, 0.5)
            elif cs == 'L' and cd == 'C':
                exit_pt = (0.5, 1); entry_pt = (0, 0.5)
        edge_cell(root, f"{P}-e{i}", f"{P}-n{s}", f"{P}-n{d}", lbl,
                  points=points, exit_pt=exit_pt, entry_pt=entry_pt)

    # caption gambar
    cap_y = Y0 + (maxrow + 1) * ROW_H + 10
    text_cell(root, f"{P}-gcap", f"<b>Gambar {spec['gambar']}</b> <i>Flowchart</i> {spec['judul']}",
              COL_X['C'] - 60, cap_y, 320, 26, align="center")

    # ---------- Tabel ----------
    mains = spec['fg_mains']; sides = spec.get('fg_sides', {})
    fg_h = 30 + (len(mains) - 1) * FG_SP + FG_D + 30
    body_h = max(fg_h, 560)
    tw = sum(COLW)
    text_cell(root, f"{P}-tcap", f"<b>Tabel {spec['tabel']}</b> <i>Flowgraph</i> {spec['judul']}",
              TBL_X, TBL_Y - 34, tw, 26, align="center")
    # header
    hdrs = ["<b><i>Flowgraph</i></b>", "<b>Rumus</b>", "<b>Jalur <i>Flowgraph</i></b>"]
    hx = TBL_X
    hdr_style = ("rounded=0;whiteSpace=wrap;html=1;fillColor=#f5f5f5;strokeColor=#000000;"
                 "fontSize=11;align=center;verticalAlign=middle;")
    body_style = ("rounded=0;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;"
                  "fontSize=10;align=left;verticalAlign=top;spacing=8;spacingTop=4;")
    for ci, (hv, cw) in enumerate(zip(hdrs, COLW)):
        cell(root, f"{P}-th{ci}", hv, hdr_style, hx, TBL_Y, cw, HDR_H)
        hx += cw
    # body: kolom rumus & jalur (dengan teks), kolom flowgraph kosong
    cell(root, f"{P}-tb0", "", body_style, TBL_X, TBL_Y + HDR_H, COLW[0], body_h)
    n = len(nodes); e = len(spec['edges']); v = e - n + 2; pdec = v - 1
    rumus = (f"Dik:<br>Jumlah <i>node</i> (n) = {n}<br>Jumlah <i>edge</i> (e) = {e}<br><br>"
             f"<b>Rumus 1</b><br>V(G) = e &#8722; n + 2<br>V(G) = {e} &#8722; {n} + 2<br>V(G) = {v}<br><br>"
             f"<b>Rumus 2</b><br>V(G) = P + 1<br>V(G) = {pdec} + 1<br>V(G) = {v}")
    cell(root, f"{P}-tb1", rumus, body_style, TBL_X + COLW[0], TBL_Y + HDR_H, COLW[1], body_h)
    jalur = ""
    for k, (pth, desc) in enumerate(spec['paths'], 1):
        jalur += f"<b>Path {k} =</b><br>{pth}<br><i>({desc})</i><br><br>"
    cell(root, f"{P}-tb2", jalur, body_style, TBL_X + COLW[0] + COLW[1], TBL_Y + HDR_H, COLW[2], body_h)

    # ---------- Flowgraph (lingkaran) ----------
    fg_cx = TBL_X + COLW[0] / 2          # sumbu utama
    fg_y0 = TBL_Y + HDR_H + 28
    fgpos = {}
    for idx, num in enumerate(mains):
        x = fg_cx - FG_D / 2
        y = fg_y0 + idx * FG_SP
        fgpos[num] = (x, y)
    main_index = {num: i for i, num in enumerate(mains)}
    for num, (sidedir, anchor) in sides.items():
        x = fg_cx - FG_D / 2 + sidedir * 58
        y = fg_y0 + anchor * FG_SP
        fgpos[num] = (x, y)
    for num, (x, y) in fgpos.items():
        cell(root, f"{P}-f{num}", str(num), FG_NODE_STYLE, x, y, FG_D, FG_D)

    def fgc(num):
        x, y = fgpos[num]
        return (x + FG_D / 2, y + FG_D / 2)

    for i, e2 in enumerate(spec['edges']):
        s, d = e2[0], e2[1]
        sxc, syc = fgc(s); dxc, dyc = fgc(d)
        points = None
        consecutive = (s in main_index and d in main_index and
                       main_index[d] == main_index[s] + 1)
        side_link = (s in sides) != (d in sides)
        if consecutive:
            pass
        elif s in sides or d in sides:
            if side_link and abs(syc - dyc) <= FG_SP * 1.6:
                pass  # garis langsung ke node samping terdekat
            else:
                sd = sides.get(s, sides.get(d, (1, 0)))[0]
                midy = (syc + dyc) / 2
                offx = fg_cx + sd * 62
                points = [(offx, midy)]
        else:
            # kedua node di rantai utama tapi tidak berurutan (mis. lompatan/batal)
            midy = (syc + dyc) / 2
            offx = fg_cx - 66 if dyc > syc else fg_cx - 66
            points = [(offx, midy)]
        edge_cell(root, f"{P}-fe{i}", f"{P}-f{s}", f"{P}-f{d}",
                  style=FG_EDGE_STYLE, points=points)

# =====================================================================
#  SPESIFIKASI 9 HALAMAN
# =====================================================================
SPECS = []

def spec_tambah(entity, judul, sec, gbr, tbl, input_label, valid_label, err_label, ok_desc, err_desc, fail_desc, tampil_label):
    return dict(
        page=f"Tambah {entity}", section=sec, judul=judul, gambar=gbr, tabel=tbl,
        nodes={
            1:  ("Mulai", 'start', 'C', 0),
            2:  (tampil_label, 'proc', 'C', 1),
            3:  (f"Klik Tombol Tambah {entity}", 'proc', 'C', 2),
            4:  (input_label, 'proc', 'C', 3),
            5:  (valid_label, 'dec', 'C', 4),
            6:  (err_label, 'proc', 'L', 4),
            7:  ("Simpan Data ke Database", 'proc', 'C', 5),
            8:  ("Apakah Query Berhasil?", 'dec', 'C', 6),
            9:  ("Tampilkan Pesan Gagal", 'proc', 'R', 6),
            10: ("Tampilkan Pesan Sukses", 'proc', 'C', 7),
            11: ("Redirect ke Halaman index.php", 'proc', 'C', 8),
            12: ("Selesai", 'end', 'C', 9),
        },
        edges=[(1,2),(2,3),(3,4),(4,5),(5,6,'Tidak'),(6,4),(5,7,'Ya'),(7,8),
               (8,10,'Ya'),(8,9,'Tidak'),(9,11),(10,11),(11,12)],
        fg_mains=[1,2,3,4,5,7,8,10,11,12],
        fg_sides={6:(-1,3.5), 9:(1,6.5)},
        paths=[
            ("1,2,3,4,5,7,8,10,11,12", ok_desc),
            ("1,2,3,4,5,6,4,5,7,8,10,11,12", err_desc),
            ("1,2,3,4,5,7,8,9,11,12", fail_desc),
        ])

def spec_edit(entity, judul, sec, gbr, tbl, input_label, valid_label, err_label, ok_desc, err_desc, fail_desc, tampil_label):
    return dict(
        page=f"Edit {entity}", section=sec, judul=judul, gambar=gbr, tabel=tbl,
        nodes={
            1:  ("Mulai", 'start', 'C', 0),
            2:  (tampil_label, 'proc', 'C', 1),
            3:  ("Klik Tombol Edit pada Data yang Dipilih", 'proc', 'C', 2),
            4:  (input_label, 'proc', 'C', 3),
            5:  (valid_label, 'dec', 'C', 4),
            6:  (err_label, 'proc', 'L', 4),
            7:  ("Update Data ke Database", 'proc', 'C', 5),
            8:  ("Apakah Query Berhasil?", 'dec', 'C', 6),
            9:  ("Tampilkan Pesan Gagal", 'proc', 'R', 6),
            10: ("Tampilkan Pesan Sukses", 'proc', 'C', 7),
            11: ("Redirect ke Halaman index.php", 'proc', 'C', 8),
            12: ("Selesai", 'end', 'C', 9),
        },
        edges=[(1,2),(2,3),(3,4),(4,5),(5,6,'Tidak'),(6,4),(5,7,'Ya'),(7,8),
               (8,10,'Ya'),(8,9,'Tidak'),(9,11),(10,11),(11,12)],
        fg_mains=[1,2,3,4,5,7,8,10,11,12],
        fg_sides={6:(-1,3.5), 9:(1,6.5)},
        paths=[
            ("1,2,3,4,5,7,8,10,11,12", ok_desc),
            ("1,2,3,4,5,6,4,5,7,8,10,11,12", err_desc),
            ("1,2,3,4,5,7,8,9,11,12", fail_desc),
        ])

def spec_hapus_sederhana(entity, judul, sec, gbr, tbl, relasi_label, relasi_msg, ok_desc, batal_desc, relasi_desc, fail_desc, tampil_label):
    return dict(
        page=f"Hapus {entity}", section=sec, judul=judul, gambar=gbr, tabel=tbl,
        nodes={
            1:  ("Mulai", 'start', 'C', 0),
            2:  (tampil_label, 'proc', 'C', 1),
            3:  ("Klik Tombol Hapus pada Data yang Dipilih", 'proc', 'C', 2),
            4:  ("Apakah Yakin Menghapus? (Konfirmasi)", 'dec', 'C', 3),
            5:  (relasi_label, 'dec', 'C', 4),
            6:  (relasi_msg, 'proc', 'R', 4),
            7:  ("Hapus Data dari Database", 'proc', 'C', 5),
            8:  ("Apakah Query Berhasil?", 'dec', 'C', 6),
            9:  ("Tampilkan Pesan Gagal", 'proc', 'R', 6),
            10: ("Tampilkan Pesan Sukses", 'proc', 'C', 7),
            11: ("Redirect ke Halaman index.php", 'proc', 'C', 8),
            12: ("Selesai", 'end', 'C', 9),
        },
        edges=[(1,2),(2,3),(3,4),(4,2,'Tidak'),(4,5,'Ya'),(5,6,'Ya'),(6,11),
               (5,7,'Tidak'),(7,8),(8,10,'Ya'),(8,9,'Tidak'),(9,11),(10,11),(11,12)],
        fg_mains=[1,2,3,4,5,7,8,10,11,12],
        fg_sides={6:(1,4.5), 9:(-1,6.5)},
        paths=[
            ("1,2,3,4,5,7,8,10,11,12", ok_desc),
            ("1,2,3,4,2,3,4,5,7,8,10,11,12", batal_desc),
            ("1,2,3,4,5,6,11,12", relasi_desc),
            ("1,2,3,4,5,7,8,9,11,12", fail_desc),
        ])

# ------------------- 1-3 : DATA IURAN -------------------
SPECS.append(spec_tambah(
    "Kategori Iuran", "Tambah Data Iuran", "4.2.2.3", "4.18", "4.26",
    "Input Nama Iuran, Tahun, Periode, Nominal, Keterangan",
    "Apakah Nama Iuran & Nominal Terisi?",
    "Tampilkan Pesan 'Nama iuran dan nominal harus diisi!'",
    "input valid \u2192 simpan berhasil \u2192 data iuran bertambah",
    "input kosong \u2192 pesan error \u2192 input ulang \u2192 simpan berhasil",
    "input valid \u2192 query gagal \u2192 pesan gagal ditampilkan",
    "Tampilkan Daftar Kategori Iuran"))

SPECS.append(spec_edit(
    "Kategori Iuran", "Edit Data Iuran", "4.2.2.4", "4.19", "4.27",
    "Form Terisi Data Lama, Ubah Nama Iuran / Tahun / Periode / Nominal",
    "Apakah ID, Nama Iuran & Nominal Valid?",
    "Tampilkan Pesan 'Data yang diedit harus valid!'",
    "data valid \u2192 update berhasil \u2192 data iuran berubah",
    "input kosong \u2192 pesan error \u2192 input ulang \u2192 update berhasil",
    "data valid \u2192 query gagal \u2192 pesan gagal ditampilkan",
    "Tampilkan Daftar Kategori Iuran"))

SPECS.append(spec_hapus_sederhana(
    "Kategori Iuran", "Hapus Data Iuran", "4.2.2.5", "4.20", "4.28",
    "Apakah Iuran Digunakan oleh Santri?",
    "Tampilkan Pesan 'Iuran tidak dapat dihapus karena digunakan santri!'",
    "konfirmasi ya \u2192 iuran tidak digunakan santri \u2192 hapus berhasil",
    "batal konfirmasi \u2192 klik hapus lagi \u2192 hapus berhasil",
    "iuran digunakan santri \u2192 data tidak dapat dihapus",
    "konfirmasi ya \u2192 query gagal \u2192 pesan gagal ditampilkan",
    "Tampilkan Daftar Kategori Iuran"))

# ------------------- 4-6 : DATA PETUGAS -------------------
SPECS.append(dict(
    page="Tambah Petugas", section="4.2.2.6", judul="Tambah Data Petugas",
    gambar="4.21", tabel="4.29",
    nodes={
        1:  ("Mulai", 'start', 'C', 0),
        2:  ("Tampilkan Daftar Petugas", 'proc', 'C', 1),
        3:  ("Klik Tombol Tambah Petugas", 'proc', 'C', 2),
        4:  ("Input Username, Nama, Email, No. Telp, Password, Level, Status", 'proc', 'C', 3),
        5:  ("Apakah Username, Nama & Password Terisi?", 'dec', 'C', 4),
        6:  ("Tampilkan Pesan 'Username, nama, dan password harus diisi!'", 'proc', 'L', 4),
        7:  ("Apakah Username Sudah Digunakan?", 'dec', 'C', 5),
        8:  ("Tampilkan Pesan 'Username sudah digunakan!'", 'proc', 'R', 5),
        9:  ("Hash Password & Simpan Data ke Database", 'proc', 'C', 6),
        10: ("Apakah Query Berhasil?", 'dec', 'C', 7),
        11: ("Tampilkan Pesan Sukses", 'proc', 'C', 8),
        12: ("Tampilkan Pesan Gagal", 'proc', 'R', 7),
        13: ("Redirect ke Halaman index.php", 'proc', 'C', 9),
        14: ("Selesai", 'end', 'C', 10),
    },
    edges=[(1,2),(2,3),(3,4),(4,5),(5,6,'Tidak'),(6,4),(5,7,'Ya'),(7,8,'Ya'),
           (8,4),(7,9,'Tidak'),(9,10),(10,11,'Ya'),(10,12,'Tidak'),(11,13),(12,13),(13,14)],
    fg_mains=[1,2,3,4,5,7,9,10,11,13,14],
    fg_sides={6:(-1,3.5), 8:(1,4.5), 12:(1,8)},
    paths=[
        ("1,2,3,4,5,7,9,10,11,13,14",
         "input valid \u2192 username tersedia \u2192 simpan berhasil"),
        ("1,2,3,4,5,6,4,5,7,9,10,11,13,14",
         "input kosong \u2192 pesan error \u2192 input ulang \u2192 simpan berhasil"),
        ("1,2,3,4,5,7,8,4,5,7,9,10,11,13,14",
         "username sudah digunakan \u2192 pesan error \u2192 ganti username \u2192 simpan berhasil"),
        ("1,2,3,4,5,7,9,10,12,13,14",
         "input valid \u2192 query gagal \u2192 pesan gagal ditampilkan"),
    ]))

SPECS.append(dict(
    page="Edit Petugas", section="4.2.2.7", judul="Edit Data Petugas",
    gambar="4.22", tabel="4.30",
    nodes={
        1:  ("Mulai", 'start', 'C', 0),
        2:  ("Tampilkan Daftar Petugas", 'proc', 'C', 1),
        3:  ("Klik Tombol Edit pada Petugas yang Dipilih", 'proc', 'C', 2),
        4:  ("Form Terisi Data Lama, Ubah Data Petugas", 'proc', 'C', 3),
        5:  ("Apakah ID, Username & Nama Valid?", 'dec', 'C', 4),
        6:  ("Tampilkan Pesan 'Data petugas yang diedit harus valid!'", 'proc', 'L', 4),
        7:  ("Apakah Username Dipakai Petugas Lain?", 'dec', 'C', 5),
        8:  ("Tampilkan Pesan 'Username sudah digunakan!'", 'proc', 'R', 5),
        9:  ("Apakah Password Baru Diisi?", 'dec', 'C', 6),
        10: ("Hash Password Baru & Sertakan pada Query", 'proc', 'R', 6),
        11: ("Update Data Petugas ke Database", 'proc', 'C', 7),
        12: ("Apakah Query Berhasil?", 'dec', 'C', 8),
        13: ("Tampilkan Pesan Sukses", 'proc', 'C', 9),
        14: ("Tampilkan Pesan Gagal", 'proc', 'R', 8),
        15: ("Redirect ke Halaman index.php", 'proc', 'C', 10),
        16: ("Selesai", 'end', 'C', 11),
    },
    edges=[(1,2),(2,3),(3,4),(4,5),(5,6,'Tidak'),(6,4),(5,7,'Ya'),(7,8,'Ya'),
           (8,4),(7,9,'Tidak'),(9,10,'Ya'),(10,11),(9,11,'Tidak'),(11,12),
           (12,13,'Ya'),(12,14,'Tidak'),(13,15),(14,15),(15,16)],
    fg_mains=[1,2,3,4,5,7,9,11,12,13,15,16],
    fg_sides={6:(-1,3.5), 8:(1,4.5), 10:(1,6.5), 14:(-1,9)},
    paths=[
        ("1,2,3,4,5,7,9,11,12,13,15,16",
         "data valid \u2192 tanpa password baru \u2192 update berhasil"),
        ("1,2,3,4,5,6,4,5,7,9,11,12,13,15,16",
         "input kosong \u2192 pesan error \u2192 input ulang \u2192 update berhasil"),
        ("1,2,3,4,5,7,8,4,5,7,9,11,12,13,15,16",
         "username dipakai petugas lain \u2192 pesan error \u2192 ganti username \u2192 update berhasil"),
        ("1,2,3,4,5,7,9,10,11,12,13,15,16",
         "password baru diisi \u2192 password di-hash \u2192 update berhasil"),
        ("1,2,3,4,5,7,9,11,12,14,15,16",
         "data valid \u2192 query gagal \u2192 pesan gagal ditampilkan"),
    ]))

SPECS.append(dict(
    page="Hapus Petugas", section="4.2.2.8", judul="Hapus Data Petugas",
    gambar="4.23", tabel="4.31",
    nodes={
        1:  ("Mulai", 'start', 'C', 0),
        2:  ("Tampilkan Daftar Petugas", 'proc', 'C', 1),
        3:  ("Klik Tombol Hapus pada Petugas yang Dipilih", 'proc', 'C', 2),
        4:  ("Apakah Yakin Menghapus? (Konfirmasi)", 'dec', 'C', 3),
        5:  ("Apakah Menghapus Akun Sendiri?", 'dec', 'C', 4),
        6:  ("Tampilkan Pesan 'Anda tidak dapat menghapus akun sendiri!'", 'proc', 'L', 4),
        7:  ("Apakah Petugas Memiliki Data Pembayaran?", 'dec', 'C', 5),
        8:  ("Ubah Status Petugas Menjadi Tidak Aktif (inactive)", 'proc', 'R', 5),
        9:  ("Hapus Data Petugas dari Database", 'proc', 'C', 6),
        10: ("Apakah Query Berhasil?", 'dec', 'C', 7),
        11: ("Tampilkan Pesan Sukses", 'proc', 'C', 8),
        12: ("Tampilkan Pesan Gagal", 'proc', 'R', 7),
        13: ("Redirect ke Halaman index.php", 'proc', 'C', 9),
        14: ("Selesai", 'end', 'C', 10),
    },
    edges=[(1,2),(2,3),(3,4),(4,2,'Tidak'),(4,5,'Ya'),(5,6,'Ya'),(6,13),
           (5,7,'Tidak'),(7,8,'Ya'),(8,13),(7,9,'Tidak'),(9,10),
           (10,11,'Ya'),(10,12,'Tidak'),(11,13),(12,13),(13,14)],
    fg_mains=[1,2,3,4,5,7,9,10,11,13,14],
    fg_sides={6:(-1,4.5), 8:(1,5.5), 12:(1,8)},
    paths=[
        ("1,2,3,4,5,7,9,10,11,13,14",
         "konfirmasi ya \u2192 bukan akun sendiri \u2192 tanpa data pembayaran \u2192 hapus berhasil"),
        ("1,2,3,4,2,3,4,5,7,9,10,11,13,14",
         "batal konfirmasi \u2192 klik hapus lagi \u2192 hapus berhasil"),
        ("1,2,3,4,5,6,13,14",
         "menghapus akun sendiri \u2192 ditolak sistem \u2192 pesan error"),
        ("1,2,3,4,5,7,8,13,14",
         "petugas memiliki data pembayaran \u2192 status diubah menjadi tidak aktif"),
        ("1,2,3,4,5,7,9,10,12,13,14",
         "konfirmasi ya \u2192 query gagal \u2192 pesan gagal ditampilkan"),
    ]))

# ------------------- 7-9 : DATA KELAS -------------------
SPECS.append(spec_tambah(
    "Kelas", "Tambah Data Kelas", "4.2.2.9", "4.24", "4.32",
    "Input Nama Kelas",
    "Apakah Nama Kelas Terisi?",
    "Tampilkan Pesan 'Nama kelas harus diisi!'",
    "input valid \u2192 simpan berhasil \u2192 data kelas bertambah",
    "nama kelas kosong \u2192 pesan error \u2192 input ulang \u2192 simpan berhasil",
    "input valid \u2192 query gagal \u2192 pesan gagal ditampilkan",
    "Tampilkan Daftar Kelas"))

SPECS.append(spec_edit(
    "Kelas", "Edit Data Kelas", "4.2.2.10", "4.25", "4.33",
    "Form Terisi Data Lama, Ubah Nama Kelas",
    "Apakah ID & Nama Kelas Valid?",
    "Tampilkan Pesan 'Nama kelas dan data yang diedit harus valid!'",
    "data valid \u2192 update berhasil \u2192 nama kelas berubah",
    "nama kelas kosong \u2192 pesan error \u2192 input ulang \u2192 update berhasil",
    "data valid \u2192 query gagal \u2192 pesan gagal ditampilkan",
    "Tampilkan Daftar Kelas"))

SPECS.append(spec_hapus_sederhana(
    "Kelas", "Hapus Data Kelas", "4.2.2.11", "4.26", "4.34",
    "Apakah Kelas Memiliki Data Santri?",
    "Tampilkan Pesan 'Kelas tidak dapat dihapus karena memiliki data santri!'",
    "konfirmasi ya \u2192 kelas tanpa santri \u2192 hapus berhasil",
    "batal konfirmasi \u2192 klik hapus lagi \u2192 hapus berhasil",
    "kelas memiliki data santri \u2192 data tidak dapat dihapus",
    "konfirmasi ya \u2192 query gagal \u2192 pesan gagal ditampilkan",
    "Tampilkan Daftar Kelas"))

# =====================================================================
#  VALIDASI & GENERATE
# =====================================================================
def validate(spec):
    edges = {(e[0], e[1]) for e in spec['edges']}
    n = len(spec['nodes']); e = len(spec['edges'])
    v = e - n + 2
    ndec = sum(1 for k, nd in spec['nodes'].items() if nd[1] == 'dec')
    assert v == ndec + 1, f"{spec['page']}: V(G) e-n+2={v} != P+1={ndec+1}"
    assert v == len(spec['paths']), f"{spec['page']}: V(G)={v} != jumlah path={len(spec['paths'])}"
    for pth, _ in spec['paths']:
        seq = [int(x) for x in pth.split(',')]
        for a, b in zip(seq, seq[1:]):
            assert (a, b) in edges, f"{spec['page']}: path edge {a}->{b} tidak ada di flowchart"
    # flowgraph positions cover all nodes
    covered = set(spec['fg_mains']) | set(spec.get('fg_sides', {}))
    assert covered == set(spec['nodes']), f"{spec['page']}: flowgraph node kurang: {set(spec['nodes'])-covered}"
    return v

mxfile = ET.Element('mxfile', host="app.diagrams.net", type="device")
print("Validasi & ringkasan:")
for i, spec in enumerate(SPECS, 1):
    v = validate(spec)
    n = len(spec['nodes']); e = len(spec['edges'])
    print(f"  {i}. {spec['page']:<22} n={n:<3} e={e:<3} V(G)={v}  path={len(spec['paths'])}")
    build_page(mxfile, f"p{i}", spec)

tree = ET.ElementTree(mxfile)
ET.indent(tree, space=" ")
out = "/home/user/webapp/whitebox/whitebox_iuran_petugas_kelas.drawio"
tree.write(out, encoding="utf-8", xml_declaration=True)
print(f"\nFile dibuat: {out}")
