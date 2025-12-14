import {
  BadgeVariant,
  BarChartData,
  ButtonType,
  ButtonVariant,
  MetaData,
  ModalAffirmationVariant,
  ModalAlertVariant,
  ModalConfirmationVariant,
  ModalPlacement,
  OptionData,
  PageStatus,
  PieChartData,
  SectionStatus,
  TabData,
  TitleVariant
} from '../models'

export declare const AccordionDefault: {
  (
    props?: {
      /** default: 'null' */
      modelValue?: number | null
    },
    context?: {
      slots?: Record<
        string,
        (slotProps: { active: number | null; toggle: (index: number) => void }) => any
      >
    }
  ): any
}
export declare const AccordionItem: {
  (
    props?: {
      title: string
      index: number
      active: [number, null]
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const BadgeDefault: {
  (
    props?: {
      /** default: 'default' */
      /** BadgeVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: BadgeVariant
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const BadgeNotification: {
  (
    props?: {
      /** default: 0 */
      value: number
      /** default: 'default' */
      /** BadgeVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: BadgeVariant
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const BreadcrumbDefault: {
  (
    props: {},
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonAdd: {
  (
    props?: {
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonChat: {
  (
    props?: {
      /** default: 0 */
      badge: number
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonClose: {
  (
    props?: {
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonDefault: {
  (
    props?: {
      /** default: 'button' */
      /** ButtonType: 'button' | 'submit' | 'reset' */
      type?: ButtonType
      /** default: 'default' */
      /** ButtonVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: ButtonVariant
      /** default: false */
      disabled?: boolean
      /** default: false */
      loading?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonDelete: {
  (
    props?: {
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonDownload: {
  (
    props?: {
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonEdit: {
  (
    props?: {
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonIcon: {
  (
    props?: {
      /** default: 'button' */
      /** ButtonType: 'button' | 'submit' | 'reset' */
      type?: ButtonType
      /** default: 'default' */
      /** ButtonVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: ButtonVariant
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonLink: {
  (
    props?: {
      /** default: 'button' */
      /** ButtonType: 'button' | 'submit' | 'reset' */
      type?: ButtonType
      /** default: 'default' */
      /** ButtonVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: ButtonVariant
      /** default: false */
      disabled?: boolean
      /** default: false */
      loading?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonMenu: {
  (
    props?: {
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonOutline: {
  (
    props?: {
      /** default: 'button' */
      /** ButtonType: 'button' | 'submit' | 'reset' */
      type?: ButtonType
      /** default: 'default' */
      /** ButtonVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: ButtonVariant
      /** default: false */
      disabled?: boolean
      /** default: false */
      loading?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonSave: {
  (
    props?: {
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ButtonView: {
  (
    props?: {
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const CardDefault: {
  (
    props?: {
      /** default: 'bg-white' */
      backgroundClass?: string
      /** default: 'p-4 sm:p-6' */
      paddingClass?: string
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ChartBar: {
  (
    props: {
      /** Required */
      /** BarChartData: {
        series: {
          name: string
          data: {
            x: string
            y: string
          }[]
        }[]
        colors: string[]
      } */
      data: BarChartData
      /** Required */
      height: number
      /** default: 8 */
      borderRadius?: number
      /** default: false */
      legend?: boolean
      /** default: 'top' */
      legendPosition?: string
      /** default: 0 */
      legendOffsetY?: number
      /** default: '' */
      ySuffix?: string
      /** default: undefined */
      yMax?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ChartPie: {
  (
    props: {
      /** Required */
      /** PieChartData: {
        labels: string[]
        values: number[]
        colors: string[]
      } */
      data: PieChartData
      /** Required */
      height: number
      /** default: false */
      legend?: boolean
      /** default: 'right' */
      legendPosition?: string
      /** default: -20 */
      legendOffsetY?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ChipDefault: {
  (
    props?: {
      /** default: '' */
      title?: string
      /** default: 'default' */
      /** BadgeVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: BadgeVariant
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const DrawerDefault: {
  (
    props: {
      /** default: '' */
      label?: string
      /** default: true */
      backdrop?: boolean
      /** default: false */
      bodyScrolling?: boolean
      /** default: 'max-w-md' */
      maxWidthClass?: string
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const DrawerMenu: {
  (
    props: {
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const EntryDefault: {
  (
    props: {
      /** Required */
      label: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldCheckbox: {
  (
    props: {
      /** default: undefined */
      initialValue?: string[] | undefined
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** Required */
      /** OptionsData[]:  {
        value: string
        label: string
      }[] */
      options: OptionData[]
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: '' */
      itemCustomClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldDate: {
  (
    props: {
      /** default: undefined */
      initialValue?: string | undefined
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** Not Required */
      minDate?: string
      /** Not Required */
      maxDate?: string
      /** default: '' */
      placeholder?: string
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldFile: {
  (
    props: {
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** default: '' */
      accept?: string
      /** default: false */
      multiple?: boolean
      /** default: '' */
      info?: string
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldInput: {
  (
    props: {
      /** default: undefined */
      initialValue?: string | number | undefined
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** default: 'text' */
      type?: 'text' | 'email' | 'password' | 'number' | 'url' | 'tel'
      /** default: '' */
      placeholder?: string
      /** default: false */
      disabled?: boolean
      /** default: 'off' */
      autocomplete?: string
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldLabel: {
  (
    props: {
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldRadio: {
  (
    props: {
      /** default: undefined */
      initialValue?: string
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** Required */
      /** OptionsData[]:  {
        value: string
        label: string
      }[] */
      options: OptionData[]
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: '' */
      itemCustomClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldSearch: {
  (
    props: {
      /** default: undefined */
      initialValue?: string
      /** Required */
      name: string
      /** default: '' */
      placeholder?: string
      /** default: false */
      disabled?: boolean
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldSelect: {
  (
    props: {
      /** default: undefined */
      initialValue?: string
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** Required */
      /** OptionsData[]:  {
        value: string
        label: string
      }[] */
      options: OptionData[]
      /** default: '' */
      placeholder?: string
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldSelectMultiple: {
  (
    props: {
      /** default: undefined */
      /** OptionsData[]:  {
        value: string
        label: string
      }[] */
      initialValue?: OptionData[]
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** default: 'initial' */
      optionsStatus?: string
      /** Required */
      /** OptionsData[]:  {
        value: string
        label: string
      }[] */
      options: OptionData[]
      /** default: '' */
      placeholder?: string
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldSelectSingle: {
  (
    props: {
      /** default: undefined */
      /** OptionsData:  {
        value: string
        label: string
      } */
      initialValue?: OptionData | undefined
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** default: 'initial' */
      optionsStatus?: string
      /** Required */
      /** OptionsData[]:  {
        value: string
        label: string
      }[] */
      options: OptionData[]
      /** default: '' */
      placeholder?: string
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldTextarea: {
  (
    props: {
      /** default: undefined */
      initialValue?: string | undefined
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** default: 4 */
      rows?: number
      /** default: '' */
      placeholder?: string
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldTime: {
  (
    props: {
      /** default: undefined */
      initialValue?: string | undefined
      /** Required */
      name: string
      /** default: '' */
      label?: string
      /** default: true */
      required?: boolean
      /** default: false */
      disabled?: boolean
      /** default: '' */
      labelCustomClass?: string
      /** default: '' */
      customClass?: string
      /** default: 0 */
      submitCount?: number
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const FieldValue: {
  (
    props: {
      /** default: '' */
      value?: string
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const IconLoading: {
  (
    props: {
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const IconMenu: {
  (
    props: {},
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ImageDefault: {
  (
    props: {
      /** Required */
      url: string
      /** default: contain */
      objectFit?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const LinkDefault: {
  (
    props: {},
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ModalAffirmation: {
  (
    props: {
      /** default: 'primary' */
      /** ModalAffirmationVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: ModalAffirmationVariant
      /** default: '' */
      confirmText?: string
      /** default: '' */
      cancelText?: string
      /** default: false */
      loading?: boolean
      /** default: 'center' */
      /** ModalPlacement: 'top-left'
      | 'top-center'
      | 'top-right'
      | 'center-left'
      | 'center'
      | 'center-right'
      | 'bottom-left'
      | 'bottom-center'
      | 'bottom-right' */
      placement?: ModalPlacement
      /** default: 'max-w-lg' */
      maxWidthClass?: string
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ModalAlert: {
  (
    props: {
      /** default: 'info' */
      /** ModalAlertVariant: 'info' | 'warning' | 'success' | 'danger' */
      variant?: ModalAlertVariant
      /** default: 'center' */
      /** ModalPlacement: 'top-left'
      | 'top-center'
      | 'top-right'
      | 'center-left'
      | 'center'
      | 'center-right'
      | 'bottom-left'
      | 'bottom-center'
      | 'bottom-right' */
      placement?: ModalPlacement
      /** default: 'max-w-lg' */
      maxWidthClass?: string
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ModalConfirmation: {
  (
    props: {
      /** default: 'primary' */
      /** ModalConfirmationVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: ModalConfirmationVariant
      /** default: '' */
      confirmText?: string
      /** default: '' */
      cancelText?: string
      /** default: false */
      loading?: boolean
      /** default: 'center' */
      /** ModalPlacement: 'top-left'
      | 'top-center'
      | 'top-right'
      | 'center-left'
      | 'center'
      | 'center-right'
      | 'bottom-left'
      | 'bottom-center'
      | 'bottom-right' */
      placement?: ModalPlacement
      /** default: 'max-w-lg' */
      maxWidthClass?: string
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ModalDefault: {
  (
    props: {
      /** default: '' */
      label?: string
      /** default: 'center' */
      /** ModalPlacement: 'top-left'
      | 'top-center'
      | 'top-right'
      | 'center-left'
      | 'center'
      | 'center-right'
      | 'bottom-left'
      | 'bottom-center'
      | 'bottom-right' */
      placement?: ModalPlacement
      /** default: true */
      isClosable?: boolean
      /** default: 'max-w-lg' */
      maxWidthClass?: string
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const PageDefault: {
  (
    props: {
      /** Required */
      /** PageStatus: 'initial' | 'loading' | 'error' | 'success' */
      pageStatus: PageStatus
      /** default: false */
      isViewerDocument?: boolean
      /** default: false */
      isDrawerDefault?: boolean
      /** default: false */
      isModalDefault?: boolean
      /** default: false */
      isModalConfirmation?: boolean
      /** default: false */
      isModalAlert?: boolean
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const SectionDefault: {
  (
    props: {
      /** Required */
      /** SectionStatus : 'initial' | 'loading' | 'error' | 'success' */
      sectionStatus: SectionStatus
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const StateError: {
  (
    props: {
      /** default: 'Terjadi kesalahan. Silakan coba lagi.' */
      message?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const StateInfo: {
  (
    props: {
      /** Required */
      message: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const StateLoading: {
  (
    props: {
      /** default: '' */
      info?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const StateResponseError: {
  (
    props: {
      /** default: 'Terjadi kesalahan. Silakan coba lagi.' */
      message?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const StateResponseInfo: {
  (
    props: {
      /** Required */
      message: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const StateResponseWarning: {
  (
    props: {
      /** Required */
      message: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const StateUnderConstruction: {
  (
    props: {},
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const StateWarning: {
  (
    props: {
      /** Required */
      message: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const TabDefault: {
  (
    props: {
      /** Required, default: [] */
      /** TabData: {
        name: string
        label: string
        key?: string
        badge?: number
      } */
      tabs: TabData[]
      /** default: '' */
      initialTab?: string
      /** default: true */
      wrapper?: boolean
      /** default: false */
      loading?: boolean
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const TableData: {
  (
    props: {
      /** default: 'left' */
      alignment?: string
      /** default: 1 */
      rowspan?: number
      /** default: 1 */
      colspan?: number
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const TableDataNone: {
  (
    props: {
      /** default: 'Data Kosong' */
      label?: string
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const TableDefault: {
  (
    props: {},
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const TableHeader: {
  (
    props: {
      /** default: 'left' */
      alignment?: string
      /** default: 1 */
      rowspan?: number
      /** default: 1 */
      colspan?: number
      /** default: '' */
      customClass?: string
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const TablePagination: {
  (
    props: {
      /** Required */
      /** MetaData: {
        currentPage: number
        lastPage: number
        perPage: number
        total: number
      } */
      pagination: MetaData
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const TitlePage: {
  (
    props: {},
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const TitleSection: {
  (
    props: {
      /** default: default */
      /** TitleVariant: 'default' | 'info' | 'warning' | 'success' | 'danger' */
      variant?: TitleVariant
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
export declare const ViewerFile: {
  (
    props: {
      /** Required */
      title: string
      /** Required */
      url: string
      /** default: true */
      wrapper: boolean
    },
    context?: {
      slots?: Record<string, () => any>
    }
  ): any
}
