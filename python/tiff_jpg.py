#!/usr/bin/env python3

import argparse
from PIL import Image

def tiff_to_jpg(input_path, output_path, quality=85):
    """
    Converte uma imagem TIFF para JPG.
    """
    with Image.open(input_path) as img:
        # TIFF pode ser CMYK, paleta, RGBA → converter para RGB
        rgb = img.convert("RGB")
        rgb.save(output_path, "JPEG", quality=quality)
        print(f"Arquivo convertido com sucesso: {output_path}")


def main():
    parser = argparse.ArgumentParser(
        description="Converter imagens TIFF para JPG usando Python + Pillow."
    )

    parser.add_argument(
        "--input",
        required=True,
        help="Caminho do arquivo TIFF de entrada"
    )

    parser.add_argument(
        "--output",
        required=True,
        help="Caminho do arquivo JPG de saída"
    )

    parser.add_argument(
        "--quality",
        type=int,
        default=85,
        help="Qualidade do JPG (0–100). Padrão = 85"
    )

    args = parser.parse_args()

    tiff_to_jpg(args.input, args.output, args.quality)


if __name__ == "__main__":
    main()
