import type { OptionRawData } from '@/models'

export const allOptionDummy = (): any => {
  return {
    data: {
      result: {
        data: allOption
      }
    }
  }
}

const allOption: OptionRawData[] = [
  { id: '1', name: 'Option 1' },
  { id: '2', name: 'Option 2' },
  { id: '3', name: 'Option 3' },
  { id: '4', name: 'Option 4' },
  { id: '5', name: 'Option 5' },
  { id: '6', name: 'Option 6' },
  { id: '7', name: 'Option 7' },
  { id: '8', name: 'Option 8' }
]
