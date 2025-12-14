import fs from 'fs'
import path from 'path'

const COMPONENTS_DIR = path.resolve('src/components')
const INDEX_FILE = path.join(COMPONENTS_DIR, 'index.ts')

function walk(dir: string): string[] {
  const entries = fs.readdirSync(dir, { withFileTypes: true })
  return entries.flatMap((entry) => {
    const res = path.resolve(dir, entry.name)
    if (entry.isDirectory()) return walk(res)
    if (entry.isFile() && entry.name.endsWith('.vue')) return [res]
    return []
  })
}

function generate() {
  const files = walk(COMPONENTS_DIR)

  const lines = files.map((filePath) => {
    const relativePath = './' + path.relative(COMPONENTS_DIR, filePath).replace(/\\/g, '/')
    const fileName = path.basename(filePath, '.vue')
    return `export { default as ${fileName} } from '${relativePath}'`
  })

  fs.writeFileSync(INDEX_FILE, lines.join('\n') + '\n')
  console.log(`✅ Generated ${INDEX_FILE}`)
}

generate()
