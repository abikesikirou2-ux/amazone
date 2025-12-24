import argparse
import base64
import json
import os
import pathlib
import time
from typing import Dict, List

import requests


NEGATIVE_SUFFIX = (
    " no brand, no branding, no logo, no watermark, no copyright, no text, "
    " no label, no trademark, plain background"
)


def slugify(text: str) -> str:
    s = ''.join(c if c.isalnum() or c in (' ', '-', '_') else ' ' for c in text)
    s = '-'.join(''.join(part.lower().strip()) for part in s.split())
    return s


def ensure_dir(p: pathlib.Path):
    p.mkdir(parents=True, exist_ok=True)


def load_catalog(path: str) -> List[Dict]:
    with open(path, 'r', encoding='utf-8') as f:
        return json.load(f)


def openai_generate_b64(api_key: str, prompt: str, size: int = 1024) -> str:
    url = "https://api.openai.com/v1/images/generations"
    headers = {
        "Authorization": f"Bearer {api_key}",
        "Content-Type": "application/json",
    }
    payload = {
        "model": "gpt-image-1",
        "prompt": prompt,
        "size": f"{size}x{size}",
        "n": 1,
        "response_format": "b64_json",
    }
    resp = requests.post(url, headers=headers, json=payload, timeout=180)
    resp.raise_for_status()
    data = resp.json()
    return data["data"][0]["b64_json"]


def main():
    parser = argparse.ArgumentParser(description="Generate studio product images via OpenAI Images API")
    parser.add_argument("--catalog", default="prompts.json", help="Path to prompts.json")
    parser.add_argument("--out", default="../../storage/app/public/produits", help="Output root directory")
    parser.add_argument("--category", default=None, help="Filter by category name")
    parser.add_argument("--per-item", type=int, default=1, help="Number of variants per item")
    parser.add_argument("--size", type=int, default=1024, choices=[512, 768, 1024], help="Square image size")
    parser.add_argument("--delay", type=float, default=0.5, help="Delay between calls (seconds)")
    args = parser.parse_args()

    api_key = os.getenv("OPENAI_API_KEY")
    if not api_key:
        raise SystemExit("Missing OPENAI_API_KEY env var")

    out_root = pathlib.Path(args.out).resolve()
    ensure_dir(out_root)

    catalog = load_catalog(args.catalog)
    for cat in catalog:
        cat_name = cat["category"]
        if args.category and args.category.lower() not in cat_name.lower():
            continue
        cat_slug = slugify(cat_name)

        for item in cat["items"]:
            item_name = item["name"]
            base_prompt = item["prompt"].strip()
            full_prompt = (
                base_prompt
                + ", high-resolution, e-commerce studio photography, evenly lit, centered, white or neutral background, sharp focus, professional lighting, product isolated," 
                + NEGATIVE_SUFFIX
            )

            item_slug = slugify(item_name)
            out_dir = out_root / cat_slug / item_slug
            ensure_dir(out_dir)

            for i in range(args.per_item):
                try:
                    b64 = openai_generate_b64(api_key, full_prompt, size=args.size)
                    img_bytes = base64.b64decode(b64)
                    out_file = out_dir / f"{item_slug}-{i+1}.png"
                    with open(out_file, "wb") as f:
                        f.write(img_bytes)
                    print(f"Saved: {out_file}")
                except requests.HTTPError as e:
                    print(f"HTTP error for {cat_name}/{item_name}: {e}")
                except Exception as ex:
                    print(f"Error for {cat_name}/{item_name}: {ex}")
                time.sleep(args.delay)

    print("Done.")


if __name__ == "__main__":
    main()
