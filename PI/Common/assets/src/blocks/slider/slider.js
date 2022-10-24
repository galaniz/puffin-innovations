/**
 * Slider block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  SelectControl,
  CheckboxControl,
  TextControl
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
const name = n + 'slider'

/* Allowed blocks */

let allowedBlocks = ['slide']

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

  const ids = []

  const blocks = select('core/block-editor').getBlocks(clientId)

  if (blocks.length) {
    blocks.forEach((block) => {
      const { clientId } = block

      ids.push(clientId)
    })
  }

  if (ids.length) {
    ownProps.attributes.ids = ids.join(',')
  }
})

/* Block */

registerBlockType(name, {
  title: 'Slider',
  category: 'theme-blocks',
  icon: 'slides',
  attributes: attr,
  edit: dataSelector(props => {
    const { attributes, setAttributes } = props

    const {
      label = def.label,
      selected = def.selected,
      loop = def.loop,
      width = def.width
    } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Slider Options'>
            <TextControl
              label='Label'
              help='Screen reader text for slides and buttons'
              value={label}
              onChange={label => setAttributes({ label })}
            />
            <SelectControl
              label='Slide Width'
              value={width}
              options={[
                { label: '100%', value: '100%' },
                { label: '50%', value: '50%' },
                { label: '33%', value: '33%' },
                { label: '25%', value: '25%' }
              ]}
              onChange={width => setAttributes({ width })}
            />
            <TextControl
              label='Selected Slide'
              help='First slide = 0'
              type='number'
              value={selected}
              onChange={selected => setAttributes({ selected })}
            />
            <CheckboxControl
              label='Loop'
              value='1'
              checked={!!loop}
              onChange={loop => setAttributes({ loop })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Slider' initialOpen={false}>
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
