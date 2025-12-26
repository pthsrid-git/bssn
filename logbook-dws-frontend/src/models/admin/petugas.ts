export interface PetugasData {
  guid: string
  name: string
  roles: string[]
}

export const petugasDataFromJson = (json: any): PetugasData => {
  return {
    guid: String(json.guid),
    name: json.name,
    roles: json.roles
  }
}
