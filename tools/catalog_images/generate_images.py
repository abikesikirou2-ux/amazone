import os
import json
import time
import base64
import argparse
from pathlib import Path

try:
    from openai import OpenAI
except ImportError:
    OpenAI = None

OUTPUT_ROOT = Path("storage/app/public/produits")

FALLBACK_PNG_BASE64 = (
    "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII="
)


def slugify(text: str) -> str:
    return (
        text.lower()
        .replace("&", "-")
        .replace(" ", "-")
        .replace(",", "")
        .replace("/", "-")
        .replace("+", "-")
    )


def ensure_output_dir(path: Path):
    path.mkdir(parents=True, exist_ok=True)


def save_png_b64(b64_data: str, out_path: Path):
    try:
        binary = base64.b64decode(b64_data)
        out_path.write_bytes(binary)
        return True
    except Exception:
        return False


def build_prompt(style: dict, category: str, product: str) -> str:
    return (
        f"Studio product photo of a {product} for e-commerce, "
        f"centered, single item, {style.get('lighting')}, background: {style.get('background')}. "
        f"High-resolution, {style.get('quality')}. {style.get('exclusions')}."
    )


def generate_image_variants(client: OpenAI, prompt: str, n: int = 3, size: str = "1024x1024"):
    images = []
    for i in range(n):
        try:
            res = client.images.generate(model="gpt-image-1", prompt=prompt, size=size)
            b64 = res.data[0].b64_json
            images.append(b64)
            time.sleep(0.4)  # gentle pacing
        except Exception as e:
            print(f"[warn] generation failed: {e}")
            images.append(FALLBACK_PNG_BASE64)
    return images


def main():
    parser = argparse.ArgumentParser(description="Generate studio-style product images for catalog")
    parser.add_argument("--prompts", default="tools/catalog_images/prompts.json", help="Path to prompts.json")
    parser.add_argument("--category", default=None, help="Generate only for a specific category slug")
    parser.add_argument("--limit", type=int, default=None, help="Limit number of products per category")
    parser.add_argument("--size", default="1024x1024", help="Image size, e.g., 1024x1024")
    args = parser.parse_args()

    # Check OpenAI availability
    if OpenAI is None:
        print("[error] openai package not installed. Run: pip install openai")
        return 1
    api_key = os.getenv("OPENAI_API_KEY")
    if not api_key:
        print("[error] OPENAI_API_KEY not set. Export it before running.")
        return 1

    client = OpenAI(api_key=api_key)

    with open(args.prompts, "r", encoding="utf-8") as f:
        data = json.load(f)
    style = data.get("style", {})
    categories = data.get("categories", {})

    for cat_slug, products in categories.items():
        if args.category and args.category != cat_slug:
            continue
        cat_dir = OUTPUT_ROOT / cat_slug
        ensure_output_dir(cat_dir)

        count = 0
        for product in products:
            if args.limit and count >= args.limit:
                break
            prod_slug = slugify(product)
            prompt = build_prompt(style, cat_slug, product)
            print(f"[info] Generating: {cat_slug}/{prod_slug}")
            b64_images = generate_image_variants(client, prompt, n=3, size=args.size)

            prod_dir = cat_dir / prod_slug
            ensure_output_dir(prod_dir)
            for idx, b64 in enumerate(b64_images, start=1):
                out_path = prod_dir / f"{prod_slug}-{idx}.png"
                ok = save_png_b64(b64, out_path)
                if not ok:
                    # save fallback 1x1 png
                    print(f"[warn] save failed, writing fallback: {out_path}")
                    save_png_b64(FALLBACK_PNG_BASE64, out_path)
            count += 1

    print("[done] Images generated under storage/app/public/produits")
    print("       Ensure public storage link: php artisan storage:link -n")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
