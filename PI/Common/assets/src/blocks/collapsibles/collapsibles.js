/**
 * Collapsibles block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  CheckboxControl
} = window.wp.components

const {
  InspectorControls,
  InnerBlocks
} = window.wp.blockEditor

const { withSelect } = window.wp.data
const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'collapsibles'

/* Allowed blocks */

let allowedBlocks = ['collapsible']

allowedBlocks = allowedBlocks.map(b => n + b)

const template = allowedBlocks.map(b => {
  return [b, {}, []]
})

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Set data */

const dataSelector = withSelect((select, ownProps) => {
  const { clientId } = ownProps

  if (!Object.getOwnPropertyDescriptor(ownProps, 'accordion_id')) {
    ownProps.attributes.accordion_id = clientId
  }
})

/* Block */

registerBlockType(name, {
  title: 'Collapsibles',
  category: 'theme-blocks',
  icon: 'plus',
  attributes: attr,
  edit: dataSelector(props => {
    const { attributes, setAttributes } = props
    const { accordion = def.accordion } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Collapsibles Options'>
            <CheckboxControl
              label='Accordion'
              help='One collapsible open at a time'
              value='1'
              checked={!!accordion}
              onChange={accordion => setAttributes({ accordion })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Collapsibles' initialOpen={false}>
          <InnerBlocks
            templateLock={false}
            allowedBlocks={allowedBlocks}
            template={template}
          />
        </PanelBody>
      </Panel>
    ]
  }),
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})
